<?php declare(strict_types=1);

namespace Product\Helper;

use Core\ElasticSearch\Api\Data\MapperInterface;

class ProductHelper implements MapperInterface
{
    public function getAttributesList(array $aggregations = []): array
    {
        $attributesList = [];

        $regexPattern = '/(agg_terms_|agg_range_)|(_options)$/';

        foreach ($aggregations as $aggregationName => $aggregationData) {
            $attributeCode = preg_filter($regexPattern, '', $aggregationName);

            if (!$attributeCode) {
                continue;
            }

            $bucketIds = [];

            foreach ($aggregationData['buckets'] as $bucket) {
                $bucketIds[] = $bucket['key'];
            }

            if (!isset($attributesList[$attributeCode])) {
                $attributesList[$attributeCode] = $bucketIds;
            }
        }

        return $attributesList;
    }

    public function getHitAggregations($aggregationValue, &$aggregationHits)
    {
        if (!$aggregationValue['buckets']) {
            return;
        }

        foreach ($aggregationValue['buckets'] as $bucket) {
            if (!isset($bucket) || !is_object($bucket)) {
                continue;
            }
            foreach ($bucket as $value) {
                $hits = $value[self::HITS_FIELD][self::HITS_FIELD] ?? [];
                if ($hits) {
                    $aggregationHits[] = $hits;
                }

                if ($value['buckets']) {
                    $this->getHitAggregations($value, $aggregationHits);
                }
            }
        }
    }

    public function getUniqueAggregations(array $aggregations = []): array
    {
        $aggregationHits = [];
        foreach ($aggregations as $aggregation) {
            if (is_object($aggregation) || $aggregation['buckets']) {
                $this->getHitAggregations($aggregation, $aggregationHits);
            }
        }

        if (!$aggregationHits) {
            return [];
        }
        $uniqueIds = [];
        $uniqueHits = [];

        foreach ($aggregationHits as $aggregationHit) {
            if ($uniqueIds[$aggregationHit['_id']]) {
                continue;
            }
            $uniqueIds[] = $aggregationHit['_id'];
            $uniqueHits[] = ['hit'];
        }
        return $uniqueHits;
    }
}

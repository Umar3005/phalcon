<?php declare(strict_types=1);

namespace Core\ElasticSearch\Helper;

use Core\ElasticSearch\Api\Data\RequestBuilderInterface;

class RequestBuilder implements RequestBuilderInterface
{
    public function getQuery(array $values, array $requiredParams): array
    {
        $query = [];

        foreach ($requiredParams as $param) {
            $query[$param] = $values[$param];
        }

        return $query;
    }

    public function getIndices(array $clusters, array $entities = null): array
    {
        $indices = [];

        if (!$entities) {
            return $clusters;
        }

        foreach ($clusters as $index) {
            foreach ($entities as $entity) {
                $indices[] = $index . self::UNDERSCORE_SEPARATOR . $entity;
            }
        }

        return $indices;
    }
}

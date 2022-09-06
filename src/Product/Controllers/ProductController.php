<?php declare(strict_types=1);

namespace Product\Controllers;

use Product\Api\ProductControllerInterface;
use Phalcon\Http\Response;
use Core\ElasticSearch\Controller;
use Product\Api\MetaDataProviderInterface;
use Product\Helper\ProductHelper;
use Product\Helper\ProductTax;

class ProductController extends Controller implements ProductControllerInterface
{
    protected ProductTax $productTax;
    protected ProductHelper $productHelper;
    protected MetaDataProviderInterface $metaDataProvider;

    public function initialize()
    {
        parent::initialize();

        $this->productTax       = $this->di->get(ProductTax::class);
        $this->productHelper    = $this->di->get(ProductHelper::class);
        $this->metaDataProvider = $this->di->get(MetaDataProviderInterface::class);
    }

    /** @inheritDoc */
    public function getProductAction(): Response
    {
        $cacheKey = $this->getCacheKey();
        $isCacheUsing = $this->config['server']['useOutputCache'];
        $isCacheExist = $this->isCacheExist($cacheKey, $isCacheUsing);

        $query = $this->getQuery([self::TRACK_HITS_FIELD => true]);
        $result = $isCacheExist ? $this->serviceCache->get($cacheKey) : $this->getResult($query);

        $hits = $result[self::HITS_FIELD][self::HITS_FIELD];
        $tagPrefixes = $this->getTagPrefixes(self::HIT_SOURCE_MAP);

        $tags = $this->getTags($hits, $tagPrefixes);

        if (!$isCacheExist) {
            $this->setCache($result, $tags);
        }
        $tag = implode(' ', $tags);

        return $this->requestHelper->getResponse($result, [Controller::HEADER_CACHE_FIELD => $tag]);
    }

    protected function getResult($query): array
    {
        $result = $this->provider->search($query);

        foreach ($result[self::HITS_FIELD][self::HITS_FIELD] as &$product) {
            $product[self::SOURCE_FIELD] = $this->productTax->calculateProductTax($product[self::SOURCE_FIELD]);
        }

        $aggregations = $result[self::AGGREGATIONS_FIELD] ?? [];
        $aggregationProcessedHits = $this->productHelper->getUniqueAggregations($aggregations);
        if ($aggregationProcessedHits) {
            $result[self::HITS_FIELD][self::HITS_FIELD] = $aggregationProcessedHits;
        }

        $attributeList = $this->productHelper->getAttributesList($aggregations);
        $result[self::ATTRIBUTE_METADATA_FIELD] = $this->metaDataProvider->getMetaData($attributeList);

        return $result;
    }
}

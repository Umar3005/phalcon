<?php declare(strict_types=1);

namespace Product\Model;

use Core\ElasticSearch\Api\ProviderInterface;
use Product\Api\MetaDataProviderInterface;
use Product\Helper\AttributeDataBuilder;

class MetaDataProvider implements MetaDataProviderInterface
{
    protected AttributeDataBuilder $attributeBuilder;
    protected ProviderInterface $attributeProvider;

    public function __construct(AttributeDataBuilder $attributeBuilder, ProviderInterface $attributeProvider)
    {
        $this->attributeBuilder  = $attributeBuilder;
        $this->attributeProvider = $attributeProvider;
    }

    /** @inheritDoc */
    public function getMetaData(array $attributesList): array
    {
        $attributeRequestData = $this->attributeBuilder->getRequestData(array_keys($attributesList));

        $attributeResponse = $this->attributeProvider->search($attributeRequestData);

        $hitSources = [];

        foreach ($attributeResponse[self::HITS_FIELD][self::HITS_FIELD] as $hit) {
            $hitSource = $hit[self::SOURCE_FIELD];
            $hitOption = $this->attributeBuilder->getHitOptions($hitSource, $attributesList);
            $hitSource[self::OPTIONS_FIELD] = $hitOption;
            $hitSources[] = array_intersect_key($hitSource, array_flip(self::METADATA_FIELDS));
        }

        return $hitSources;
    }
}

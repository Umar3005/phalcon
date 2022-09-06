<?php declare(strict_types=1);

namespace Product\Helper;

use Product\Api\AttributeDataInterface;

class AttributeDataBuilder implements AttributeDataInterface
{
    public function getRequestData(array $attributesList): array
    {
        return [
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'terms' => [
                                'attribute_code' => $attributesList,
                            ],
                        ],
                    ],
                ],
            ],
            'type'  => 'attribute',
            'size'  => self::DEFAULT_SIZE,
            'index' => ['vue_storefront_catalog_attribute'],
        ];
    }

    public function getHitOptions(array $hitSource, array $attributesList): array
    {
        $attributeOptionsIds = $attributesList[$hitSource['attribute_code']];
        $optionValues = array_column($hitSource['options'], 'value');
        $includedOptionValues = array_intersect($optionValues, $attributeOptionsIds);
        $optionCorrectValues = array_intersect_key($hitSource['options'], $includedOptionValues);

        return array_values($optionCorrectValues);
    }
}

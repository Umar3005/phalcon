<?php declare(strict_types=1);

namespace Product\Api;

interface AttributeDataInterface
{
    const DEFAULT_SIZE = 100;

    public function getRequestData(array $attributesList): array;
}

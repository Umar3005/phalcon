<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api\Data;

interface RequestBuilderInterface extends MapperInterface
{
    public function getQuery(array $values, array $requiredParams): array;

    public function getIndices(array $clusters, array $entities = null): array;
}

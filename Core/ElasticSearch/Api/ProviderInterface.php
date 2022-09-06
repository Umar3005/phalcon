<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api;

interface ProviderInterface
{
    public function search(array $query): array;
}

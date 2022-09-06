<?php declare(strict_types=1);

namespace Core\ElasticSearch;

use Core\ElasticSearch\Api\ProviderInterface;
use Core\ElasticSearch\Handler\ElasticSearchClientHandler;

class Provider implements ProviderInterface
{
    protected ElasticSearchClientHandler $elasticSearchHandler;

    public function __construct(ElasticSearchClientHandler $elasticSearchHandler)
    {
        $this->elasticSearchHandler = $elasticSearchHandler;
    }

    public function search(array $query): array
    {
        $elasticSearchClient = $this->elasticSearchHandler->getElasticClient();
        return $elasticSearchClient->search($query);
    }
}

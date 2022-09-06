<?php declare(strict_types=1);

namespace Core\ElasticSearch\Handler;

use Core\ElasticSearch\Api\Data\HandlerInterface;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Client;

class ElasticSearchClientHandler implements HandlerInterface
{
    protected Client $client;
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getElasticClient(): Client
    {
        if (!isset($this->clientBuilder)) {
            $this->client = ClientBuilder::create()->setHosts($this->getHost($this->config))->build();
        }

        return $this->client;
    }

    protected function getHost(array $config): array
    {
        $port     = $config[self::PORT_FIELD];
        $host     = $config[self::HOST_FIELD];
        $protocol = $config[self::PROTOCOL_FIELD];

        return [$protocol . '://' . $host . ':' . $port];
    }
}

<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api\Data;

use Elasticsearch\Client;

interface HandlerInterface
{
    const PORT_FIELD     = 'port';
    const HOST_FIELD     = 'host';
    const PROTOCOL_FIELD = 'protocol';

    public function getElasticClient(): Client;
}

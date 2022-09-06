<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api\Data;

interface RequestValidationInterface
{
    const CLUSTER_ERROR      = 'No index name given in the URL. Please do use following URL format: /api/catalog/<index_name>/<entity_type>_search';
    const SEARCH_FILED_ERROR = 'Please do use following URL format: /api/catalog/<index_name>/_search';

    const AVAILABLE_METHODS = ['POST', 'GET', 'OPTIONS'];
}

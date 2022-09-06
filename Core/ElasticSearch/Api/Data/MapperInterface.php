<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api\Data;

interface MapperInterface
{
    const SLASH_SEPARATOR      = '/';
    const ELASTIC_SEARCH       = 'elasticsearch';
    const UNDERSCORE_SEPARATOR = '_';
    const COMA_SEPARATOR       = ',';

    const HITS_FIELD            = 'hits';
    const BODY_FIELD            = 'body';
    const SIZE_FIELD            = 'size';
    const AGGS_FIELD            = 'aggs';
    const QUERY_FIELD           = 'query';
    const INDEX_FIELD           = 'index';
    const SEARCH_FIELD          = '_search';
    const CLUSTER_FIELD         = 'clusters';
    const INDICES_FIELD         = 'indices';
    const ENTITIES_FIELD        = 'entities';
    const INDEX_TYPES_FIELD     = 'indexTypes';
    const SOURCE_INCLUDES_FIELD = '_source_includes';
    const SOURCE_EXCLUDES_FIELD = '_source_excludes';
}

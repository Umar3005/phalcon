<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api\Data;

interface ControllerInterface extends MapperInterface
{
    const TAGS_PREFIX        = 'tags';
    const SOURCE_FIELD       = '_source';
    const TRACK_HITS_FIELD   = 'track_total_hits';
    const CACHE_KEY_PREFIX   = 'api_';
    const HEADER_CACHE_FIELD = 'X-VS-Cache-Tags';

    const HASH_ALGORITHM = 'sha3-224';

    public function getQuery(array $params = []);

    public function isCacheExist(string $hash, bool $isCacheUsing): bool;

    public function setCache(array $data, array $tags);

    public function getTagPrefix(string $entityType): string;
}

<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api;

use Core\ElasticSearch\Entity;

interface EntityBuilderInterface
{
    const DEFAULT_MAP = [
        'body',
        'from',
        'size',
        'sort',
        'index',
        '_source_includes',
        '_source_excludes',
        'track_total_hits'
    ];

    const PARAMS_MAP = [
        '_source_includes' => '_source_include',
        '_source_excludes' => '_source_exclude',
    ];

    public function build(array $body): Entity;

    public function setEntity(array $body): self;

    public function getEntity(): Entity;

    public function setAdditionalData(array $additionalData): self;

    public function getAdditionalData(): array;
}

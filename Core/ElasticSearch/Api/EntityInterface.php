<?php declare(strict_types=1);

namespace Core\ElasticSearch\Api;

interface EntityInterface
{
    public function setData($key, $value = null): self;

    public function getData($key = '');
}

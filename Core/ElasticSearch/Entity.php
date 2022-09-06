<?php declare(strict_types=1);

namespace Core\ElasticSearch;

use Core\ElasticSearch\Api\EntityInterface;

class Entity implements EntityInterface
{
    protected array $data = [];

    public function setData($key, $value = null): self
    {
        if ($key === (array) $key) {
            $this->data = $key;
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function getData($key = '')
    {
        if ($key === '') {
            return $this->data;
        }

        if ($this->data[$key]) {
            return $this->data[$key];
        }
        return null;
    }
}

<?php declare(strict_types=1);

namespace Core\ElasticSearch\Helper;

use Core\ElasticSearch\Api\EntityBuilderInterface;
use Core\ElasticSearch\Entity;

class EntityBuilder implements EntityBuilderInterface
{
    protected array $additionalData = [];
    protected Entity $entity;

    public function __construct()
    {
        $this->entity = new Entity();
    }

    public function build(array $body): Entity
    {
        return $this->setEntity($body)->getEntity();
    }

    public function setEntity(array $body): self
    {
        $map = array_merge(self::DEFAULT_MAP, $this->additionalData);

        foreach ($map as $field) {
            if (isset($body[$field])) {
                $this->entity->setData($field, $body[$field]);
            }
        }

        return $this;
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function setAdditionalData(array $additionalData): self
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    public function getAdditionalData(): array
    {
        return $this->additionalData;
    }
}

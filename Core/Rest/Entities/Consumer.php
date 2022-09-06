<?php declare(strict_types=1);

namespace Core\Rest\Entities;

use Core\Rest\Api\Data\ConsumerInterface;

class Consumer implements ConsumerInterface
{
    protected string $public;
    protected string $secret;
    protected string $hashMethod;

    public function __construct(array $config)
    {
        $this
            ->setPublic($config[self::CONSUMER_KEY_FIELD])
            ->setSecret($config[self::CONSUMER_SECRET_FIELD])
            ->setHashMethod($config[self::HASH_METHOD_FIELD] ?? self::DEFAULT_HASH_METHOD);
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;
        return $this;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setPublic(string $public): self
    {
        $this->public = $public;
        return $this;
    }

    public function getPublic(): string
    {
        return $this->public;
    }

    public function setHashMethod(string $hashMethod): self
    {
        $this->hashMethod = $hashMethod;
        return $this;
    }

    public function getHashMethod(): string
    {
        return $this->hashMethod;
    }
}

<?php declare(strict_types=1);

namespace Core\Rest\Entities;

use Core\Rest\Api\Data\TokenInterface;

class Token implements TokenInterface
{
    protected string $publicKey;
    protected string $secretKey;

    public function __construct(array $config)
    {
        $this
            ->setPublicKey($config[self::ACCESS_TOKEN_FIELD])
            ->setSecretKey($config[self::ACCESS_TOKEN_SECRET_FIELD]);
    }

    public function setPublicKey(string $publicKey): self
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function setSecretKey(string $secretKey): self
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}

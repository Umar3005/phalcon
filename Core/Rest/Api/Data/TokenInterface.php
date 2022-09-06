<?php declare(strict_types=1);

namespace Core\Rest\Api\Data;

interface TokenInterface
{
    const ACCESS_TOKEN_FIELD        = 'accessToken';
    const ACCESS_TOKEN_SECRET_FIELD = 'accessTokenSecret';

    public function setPublicKey(string $publicKey): self;

    public function getPublicKey(): string;

    public function setSecretKey(string $secretKey): self;

    public function getSecretKey(): string;
}

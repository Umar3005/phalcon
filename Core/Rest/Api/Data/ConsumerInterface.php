<?php declare(strict_types=1);

namespace Core\Rest\Api\Data;

interface ConsumerInterface
{
    const HASH_METHOD_FIELD     = 'HMAC-SHA1';
    const CONSUMER_KEY_FIELD    = 'consumerKey';
    const DEFAULT_HASH_METHOD   = 'HMAC-SHA1';
    const CONSUMER_SECRET_FIELD = 'consumerSecret';
    const TOKEN_FIELD           = 'token';
    const OAUTH_TYPE            = 3;

    public function setSecret(string $secret): self;

    public function getSecret(): string;

    public function setPublic(string $public): self;

    public function getPublic(): string;

    public function setHashMethod(string $hashMethod): self;

    public function getHashMethod(): string;
}

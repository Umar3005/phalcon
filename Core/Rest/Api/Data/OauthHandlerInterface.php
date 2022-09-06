<?php declare(strict_types=1);

namespace Core\Rest\Api\Data;

use OAuth;
use OAuthException;

interface OauthHandlerInterface
{
    /** @throws OAuthException */
    public function getOauth(
        string $sigMethod = ConsumerInterface::HASH_METHOD_FIELD,
        int $oauthType = ConsumerInterface::OAUTH_TYPE
    ): OAuth;
}

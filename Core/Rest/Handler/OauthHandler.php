<?php declare(strict_types=1);

namespace Core\Rest\Handler;

use Core\Rest\Api\Data\OauthHandlerInterface;
use Core\Rest\Entities\Consumer;
use Core\Rest\Api\Data\ConsumerInterface;
use Core\Rest\Entities\Token;
use OAuth;
use OAuthException;

class OauthHandler implements OauthHandlerInterface
{
    protected OAuth $oauth;
    protected Consumer $consumer;
    protected Token $token;

    public function __construct(array $config)
    {
        $this->token    = new Token($config);
        $this->consumer = new Consumer($config);
    }

    /** @throws OAuthException */
    public function getOauth(
        string $sigMethod = ConsumerInterface::HASH_METHOD_FIELD,
        int $oauthType = ConsumerInterface::OAUTH_TYPE
    ): OAuth {
        if (!isset($this->oauth)) {
            $public = $this->consumer->getPublic();
            $secret = $this->consumer->getSecret();
            $this->oauth = new OAuth($public, $secret, $sigMethod, $oauthType);
            $this->oauth->setToken($this->token->getPublicKey(), $this->token->getSecretKey());
        }
        return $this->oauth;
    }
}

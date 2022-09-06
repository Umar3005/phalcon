<?php declare(strict_types=1);

namespace Cart\Model;

use Cart\Api\CartProviderInterface;
use Core\Rest\Api\RestClientInterface;
use Phalcon\Http\Request\Method;

class CartProvider implements CartProviderInterface
{
    protected RestClientInterface $restClient;

    public function __construct(RestClientInterface $restClient)
    {
        $this->restClient = $restClient;
    }

    /** @inheritDoc */
    public function create(string $url, array $body, string $token = null): array
    {
        return $this->restClient->send($url, Method::POST, $body, $token);
    }

    /** @inheritDoc */
    public function pull(string $url, array $body, string $token = null): array
    {
        return $this->restClient->send($url, Method::GET, $body, $token);
    }

    /** @inheritDoc */
    public function update(string $url, array $body, string $token = null): array
    {
        return $this->restClient->send($url, Method::POST, $body, $token);
    }

    /** @inheritDoc */
    public function delete(string $url, array $body = [], string $token = null): array
    {
        return $this->restClient->send($url, Method::DELETE, $body, $token);
    }

    /** @inheritDoc */
    public function getPaymentMethods(string $url, array $body, string $token = null): array
    {
        return $this->restClient->send($url, Method::GET, $body, $token);
    }
}

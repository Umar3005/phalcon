<?php declare(strict_types=1);

namespace CartCoupon\Model;

use CartCoupon\Api\CartCouponProviderInterface;
use Core\Rest\RestClient;
use Phalcon\Http\Request\Method;

class CartCouponProvider implements CartCouponProviderInterface
{
    protected RestClient $restClient;

    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /** @inheritDoc */
    public function apply(string $url, array $body, string $token = null): array
    {
        return $this->restClient->send($url, Method::PUT, $body, $token);
    }

    /** @inheritDoc */
    public function delete(string $url, array $body, string $token = null): array
    {
        return $this->restClient->send($url, Method::DELETE, [], $token);
    }
}

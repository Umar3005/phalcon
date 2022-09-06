<?php declare(strict_types=1);

namespace CartCoupon\Api;

use Exception;

interface CartCouponProviderInterface
{
    /** @throws Exception */
    public function apply(string $url, array $body, string $token = null): array;

    /** @throws Exception */
    public function delete(string $url, array $body, string $token = null): array;
}

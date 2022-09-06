<?php declare(strict_types=1);

namespace Cart\Api;

use Exception;

interface CartProviderInterface
{
    /** @throws Exception */
    public function create(string $url, array $body, string $token = null): array;

    /** @throws Exception */
    public function pull(string $url, array $body, string $token = null): array;

    /** @throws Exception */
    public function update(string $url, array $body, string $token = null): array;

    /** @throws Exception */
    public function delete(string $url, array $body = [], string $token = null): array;

    /** @throws Exception */
    public function getPaymentMethods(string $url, array $body, string $token = null): array;
}

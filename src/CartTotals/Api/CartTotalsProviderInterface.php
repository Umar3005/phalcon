<?php declare(strict_types=1);

namespace CartTotals\Api;

use Exception;

interface CartTotalsProviderInterface
{
    /** @throws Exception */
    public function getTotals(string $url, array $body, string $token = null): array;
}

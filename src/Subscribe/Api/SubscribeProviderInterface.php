<?php declare(strict_types=1);

namespace Subscribe\Api;

use Exception;

interface SubscribeProviderInterface
{
    /** @throws Exception */
    public function subscribe(string $url, array $body): array;
}

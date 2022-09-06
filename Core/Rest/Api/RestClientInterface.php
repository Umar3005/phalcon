<?php declare(strict_types=1);

namespace Core\Rest\Api;

use Exception;

interface RestClientInterface
{
    /** @throws Exception */
    public function send(
        string $url,
        string $httpMethod,
        array $bodyData = [],
        $requestToken = null,
        string $contentTypeFormat = 'application/json; charset=utf-8'
    ): array;

    public function getEndpointUrl(string $url): string;

    public function setServerUrl(string $serverUrl): self;

    public function getServerUrl(): string;

    public function setHeaders(array $headers);
}

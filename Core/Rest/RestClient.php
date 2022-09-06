<?php declare(strict_types=1);

namespace Core\Rest;

use Core\Rest\Api\RestClientInterface;
use Core\Rest\Handler\OauthHandler;
use Exception;
use OAuthException;
use Phalcon\Http\Client\Provider\Curl;

class RestClient implements RestClientInterface
{
    protected string $serverUrl;
    protected OauthHandler $oauthHandler;
    protected Curl $client;

    public function __construct(array $config)
    {
        $this->setServerUrl($config['url']);

        $this->client       = new Curl();
        $this->oauthHandler = new OauthHandler($config);
    }

    /** @throws Exception */
    public function send(
        string $url,
        string $httpMethod,
        array $bodyData = [],
        $requestToken = null,
        string $contentTypeFormat = 'application/json; charset=utf-8'
    ): array {
        $endpoint = $this->getEndpointUrl($url);
        $headers = $this->prepareHeaders($requestToken, $httpMethod, $endpoint, $contentTypeFormat);
        $this->setHeaders($headers);

        $isJsonFormat = !in_array($httpMethod, ['GET', 'DELETE']);

        $preparedData = $isJsonFormat ? json_encode($bodyData) : $bodyData;
        $requestMethod = strtolower($httpMethod);

        $response = $this->client->$requestMethod($endpoint, $preparedData);

        return [
            'body'   => json_decode($response->body, true),
            'header' => (array) $response->header
        ];
    }

    public function getEndpointUrl(string $url): string
    {
        return $this->getServerUrl() . $url;
    }

    public function setServerUrl(string $serverUrl): self
    {
        $this->serverUrl = $serverUrl;
        return $this;
    }

    public function getServerUrl(): string
    {
        return $this->serverUrl;
    }

    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $header) {
            $this->client->header->set($name, $header);
        }
    }

    /** @throws OAuthException */
    protected function prepareHeaders(
        $requestToken,
        string $httpMethod,
        string $requestUrl,
        string $contentTypeFormat
    ): array {
        $headers['Authorization'] = $requestToken
            ? 'Bearer ' . $requestToken
            : $this->oauthHandler->getOauth()->getRequestHeader($httpMethod, $requestUrl);

        if ($contentTypeFormat) {
            $headers['Content-Type'] = $contentTypeFormat;
        }

        return $headers;
    }
}

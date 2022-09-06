<?php declare(strict_types=1);

namespace Subscribe\Model;

use Core\Rest\Api\RestClientInterface;
use Subscribe\Api\SubscribeProviderInterface;
use Subscribe\Helper\RequestValidation;
use Exception;
use Phalcon\Http\Request\Method;

class SubscribeProvider implements SubscribeProviderInterface
{
    protected RestClientInterface $restClient;
    protected RequestValidation $requestValidation;

    public function __construct(RestClientInterface $restClient, RequestValidation $requestValidation)
    {
        $this->restClient        = $restClient;
        $this->requestValidation = $requestValidation;
    }

    /** @throws Exception */
    public function subscribe(string $url, array $body): array
    {
        $this->requestValidation->validateEmail($body);

        return $this->restClient->send($url, Method::POST, $body);
    }
}

<?php declare(strict_types=1);

namespace CartTotals\Model;

use CartTotals\Api\CartTotalsProviderInterface;
use Core\Rest\Api\Data\ResponseHelperInterface;
use Core\Rest\Api\Data\UrlHandlerInterface;
use Core\Rest\Api\RestClientInterface;
use Phalcon\Http\Request\Method;

class CartTotalsProvider implements CartTotalsProviderInterface
{
    protected RestClientInterface $restClient;
    protected UrlHandlerInterface $urlHandler;
    protected ResponseHelperInterface $responseHelper;

    public function __construct(
        RestClientInterface $restClient,
        UrlHandlerInterface $urlHandler,
        ResponseHelperInterface $responseHelper
    ) {
        $this->restClient     = $restClient;
        $this->urlHandler     = $urlHandler;
        $this->responseHelper = $responseHelper;
    }

    /** @inheritDoc */
    public function getTotals(string $url, array $body, string $token = null): array
    {
        return $this->restClient->send($url, Method::GET, [], $token);
    }
}

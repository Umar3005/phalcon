<?php declare(strict_types=1);

namespace CartTotals\Controllers;

use CartTotals\Api\CartTotalsControllerInterface;
use CartTotals\Api\RestApiInterface;
use Core\Rest\Api\Data\ConsumerInterface;
use Core\Rest\Api\Data\ResponseHelperInterface;
use Core\Rest\Api\Data\UrlHandlerInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use CartTotals\Api\CartTotalsProviderInterface;

class CartTotalsController extends Controller implements CartTotalsControllerInterface
{
    protected CartTotalsProviderInterface $provider;
    protected UrlHandlerInterface $urlHandler;
    protected ResponseHelperInterface $responseHelper;

    public function initialize()
    {
        $this->provider       = $this->container->get(CartTotalsProviderInterface::class);
        $this->urlHandler     = $this->container->get(UrlHandlerInterface::class);
        $this->responseHelper = $this->container->get(ResponseHelperInterface::class);
    }

    /** @inheritDoc */
    public function createAction(): Response
    {
        $requestParameters = $this->request->get();
        $url = $this->urlHandler->getUrl(RestApiInterface::TOTALS, $requestParameters);

        $response = $this->provider->getTotals($url, [], $requestParameters[ConsumerInterface::TOKEN_FIELD]);

        return $this->responseHelper->getResponse($response);
    }
}

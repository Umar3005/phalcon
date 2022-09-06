<?php declare(strict_types=1);

namespace Cart\Controllers;

use Cart\Api\CartControllerInterface;
use Cart\Api\RestApiInterface;
use Core\Rest\Api\Data\ConsumerInterface;
use Core\Rest\Api\Data\ResponseHelperInterface;
use Core\Rest\Api\Data\UrlHandlerInterface;
use Phalcon\Http\Response;
use Cart\Api\CartProviderInterface;
use Phalcon\Mvc\Controller;

class CartController extends Controller implements CartControllerInterface
{
    protected CartProviderInterface $provider;
    protected UrlHandlerInterface $urlHandler;
    protected ResponseHelperInterface $responseHelper;

    public function initialize()
    {
        $this->provider       = $this->container->get(CartProviderInterface::class);
        $this->urlHandler     = $this->container->get(UrlHandlerInterface::class);
        $this->responseHelper = $this->container->get(ResponseHelperInterface::class);
    }

    /** @inheritDoc */
    public function createAction(): Response
    {
        $requestParameters = $this->request->get();
        $url = $this->urlHandler->getUrl(RestApiInterface::CREATE, $requestParameters);

        $response = $this->provider->create($url, [], $requestParameters[ConsumerInterface::TOKEN_FIELD]);

        return $this->responseHelper->getResponse($response);
    }

    /** @inheritDoc */
    public function pullAction(): Response
    {
        $requestParameters = $this->request->get();
        $url = $this->urlHandler->getUrl(RestApiInterface::PULL, $requestParameters);

        $response = $this->provider->pull($url, [], $requestParameters[ConsumerInterface::TOKEN_FIELD]);

        return $this->responseHelper->getResponse($response);
    }

    /** @inheritDoc */
    public function updateAction(): Response
    {
        $requestData = $this->request->getJsonRawBody(true);
        $requestParameters = array_merge($requestData, $this->request->get());
        $url = $this->urlHandler->getUrl(RestApiInterface::UPDATE, $requestParameters);

        $response = $this->provider->update($url, $requestData, $requestParameters[ConsumerInterface::TOKEN_FIELD]);

        return $this->responseHelper->getResponse($response);
    }

    /** @inheritDoc */
    public function deleteAction(): Response
    {
        $requestData = $this->request->getJsonRawBody(true);
        $requestParameters = array_merge($requestData, $this->request->get());
        $requestParameters[self::ITEM_ID_FIELD] = $requestData[self::CART_ITEM_FIELD][self::ITEM_ID_FIELD];
        $url = $this->urlHandler->getUrl(RestApiInterface::DELETE, $requestParameters);

        $response = $this->provider->delete($url, [], $requestParameters[ConsumerInterface::TOKEN_FIELD]);

        return $this->responseHelper->getResponse($response);
    }

    /** @inheritDoc */
    public function getPaymentMethodsAction(): Response
    {
        $requestParameters = $this->request->get();
        $url = $this->urlHandler->getUrl(RestApiInterface::PAYMENT, $requestParameters);

        $response = $this->provider->getPaymentMethods($url, [], $requestParameters[ConsumerInterface::TOKEN_FIELD]);

        return $this->responseHelper->getResponse($response);
    }
}

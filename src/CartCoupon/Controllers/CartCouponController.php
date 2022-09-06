<?php declare(strict_types=1);

namespace CartCoupon\Controllers;

use CartCoupon\Api\CartCouponControllerInterface;
use CartCoupon\Api\RestApiInterface;
use Core\Rest\Api\Data\ConsumerInterface;
use Core\Rest\Api\Data\ResponseHelperInterface;
use Core\Rest\Api\Data\UrlHandlerInterface;
use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use CartCoupon\Api\CartCouponProviderInterface;

class CartCouponController extends Controller implements CartCouponControllerInterface
{
    protected CartCouponProviderInterface $provider;
    protected UrlHandlerInterface $urlHandler;
    protected ResponseHelperInterface $responseHelper;

    public function initialize()
    {
        $this->provider       = $this->container->get(CartCouponProviderInterface::class);
        $this->urlHandler     = $this->container->get(UrlHandlerInterface::class);
        $this->responseHelper = $this->container->get(ResponseHelperInterface::class);
    }

    /** @inheritDoc */
    public function applyAction(): Response
    {
        $requestParameters = $this->request->get();
        $url = $this->urlHandler->getUrl(RestApiInterface::APPLY, $requestParameters);

        $response = $this->provider->apply($url, [], $requestParameters[ConsumerInterface::TOKEN_FIELD]);

        return $this->responseHelper->getResponse($response);
    }

    /** @inheritDoc */
    public function deleteAction(): Response
    {
        $requestParameters = $this->request->get();
        $url = $this->urlHandler->getUrl(RestApiInterface::DELETE, $requestParameters);

        $response = $this->provider->delete($url, [], $requestParameters[ConsumerInterface::TOKEN_FIELD]);

        return $this->responseHelper->getResponse($response);
    }
}

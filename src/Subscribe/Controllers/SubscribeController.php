<?php declare(strict_types=1);

namespace Subscribe\Controllers;

use Core\Rest\Api\Data\ResponseHelperInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Subscribe\Api\RestApiInterface;
use Subscribe\Api\SubscribeControllerInterface;
use Subscribe\Api\SubscribeProviderInterface;

class SubscribeController extends Controller implements SubscribeControllerInterface
{
    protected SubscribeProviderInterface $provider;
    protected ResponseHelperInterface $responseHelper;

    public function initialize()
    {
        $this->provider       = $this->container->get(SubscribeProviderInterface::class);
        $this->responseHelper = $this->container->get(ResponseHelperInterface::class);
    }

    /** @inheritDoc */
    public function subscribeAction(): Response
    {
        $requestBody = $this->request->getJsonRawBody(true);

        $response = $this->provider->subscribe(RestApiInterface::SUBSCRIBE, $requestBody);

        return $this->responseHelper->getResponse($response);
    }
}

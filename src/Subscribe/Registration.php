<?php declare(strict_types=1);

namespace Subscribe;

use Core\Rest\Api\RestClientInterface;
use Core\Rest\Helper\ResponseHelper;
use Core\Rest\RestRegistration;
use Phalcon\Di\DiInterface;
use Subscribe\Api\SubscribeProviderInterface;
use Subscribe\Model\SubscribeProvider;
use Subscribe\Helper\RequestValidation;

class Registration extends RestRegistration
{
    public function registerServices(DiInterface $container)
    {
        parent::registerServices($container);

        $container->set(RequestValidation::class, function () use ($container) {
            $responseHelper = $container->get(ResponseHelper::class);

            return new RequestValidation($responseHelper);
        });

        $container->set(SubscribeProviderInterface::class, function () use ($container) {
            $restClient        = $container->get(RestClientInterface::class);
            $requestValidation = $container->get(RequestValidation::class);

            return new SubscribeProvider($restClient, $requestValidation);
        });
    }
}

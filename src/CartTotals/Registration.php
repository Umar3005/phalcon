<?php declare(strict_types=1);

namespace CartTotals;

use Core\Rest\Api\Data\ResponseHelperInterface;
use Core\Rest\Api\Data\UrlHandlerInterface;
use Core\Rest\Api\RestClientInterface;
use Core\Rest\RestRegistration;
use Phalcon\Di\DiInterface;
use CartTotals\Api\CartTotalsProviderInterface;
use CartTotals\Model\CartTotalsProvider;

class Registration extends RestRegistration
{
    public function registerServices(DiInterface $container)
    {
        parent::registerServices($container);

        $container->set(CartTotalsProviderInterface::class, function () use ($container) {
            $restClient     = $container->get(RestClientInterface::class);
            $urlHandler     = $container->get(UrlHandlerInterface::class);
            $responseHelper = $container->get(ResponseHelperInterface::class);

            return new CartTotalsProvider($restClient, $urlHandler, $responseHelper);
        });
    }
}

<?php declare(strict_types=1);

namespace Cart;

use Cart\Api\CartProviderInterface;
use Core\Rest\Api\RestClientInterface;
use Core\Rest\RestRegistration;
use Phalcon\Di\DiInterface;
use Cart\Model\CartProvider;

class Registration extends RestRegistration
{
    public function registerServices(DiInterface $container)
    {
        parent::registerServices($container);

        $container->set(CartProviderInterface::class, function () use ($container) {
            $restClient = $container->get(RestClientInterface::class);

            return new CartProvider($restClient);
        });
    }
}

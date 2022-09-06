<?php declare(strict_types=1);

namespace CartCoupon;

use CartCoupon\Api\CartCouponProviderInterface;
use Core\Rest\Api\RestClientInterface;
use Core\Rest\RestRegistration;
use Phalcon\Di\DiInterface;
use CartCoupon\Model\CartCouponProvider;

class Registration extends RestRegistration
{
    public function registerServices(DiInterface $container)
    {
        parent::registerServices($container);

        $container->set(CartCouponProviderInterface::class, function () use ($container) {
            $restClient = $container->get(RestClientInterface::class);

            return new CartCouponProvider($restClient);
        });
    }
}

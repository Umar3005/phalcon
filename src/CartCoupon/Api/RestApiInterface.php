<?php declare(strict_types=1);

namespace CartCoupon\Api;

use Core\Rest\Api\Data\UserRoles;

interface RestApiInterface
{
    const APPLY = [
        UserRoles::ADMIN    => '/V1/carts/{cartId}/coupons/{coupon}',
        UserRoles::GUEST    => '/V1/guest-carts/{cartId}/coupons/{coupon}',
        UserRoles::CUSTOMER => '/V1/carts/mine/coupons/{coupon}',
    ];

    const DELETE = [
        UserRoles::ADMIN    => '/V1/carts/{cartId}/coupons',
        UserRoles::GUEST    => '/V1/guest-carts/{cartId}/coupons',
        UserRoles::CUSTOMER => '/V1/carts/mine/coupons',
    ];
}

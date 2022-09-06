<?php declare(strict_types=1);

namespace Cart\Api;

use Core\Rest\Api\Data\UserRoles;

interface RestApiInterface
{
    const CREATE = [
        UserRoles::ADMIN    => '/V1/customers/{customerId}/carts',
        UserRoles::GUEST    => '/V1/guest-carts',
        UserRoles::CUSTOMER => '/V1/carts/mine',
    ];

    const PULL = [
        UserRoles::ADMIN    => '/V1/carts/{cartId}/items',
        UserRoles::GUEST    => '/V1/guest-carts/{cartId}/items',
        UserRoles::CUSTOMER => '/V1/carts/mine/items',
    ];

    const UPDATE = [
        UserRoles::ADMIN    => '/V1/carts/{cartId}/items',
        UserRoles::GUEST    => '/V1/guest-carts/{cartId}/items',
        UserRoles::CUSTOMER => '/V1/carts/mine/items',
    ];

    const DELETE = [
        UserRoles::ADMIN    => '/V1/carts/{cartId}/items/{item_id}',
        UserRoles::GUEST    => '/V1/guest-carts/{cartId}/items/{item_id}',
        UserRoles::CUSTOMER => '/V1/carts/mine/items/{item_id}',
    ];

    const PAYMENT = [
        UserRoles::GUEST    => '/V1/guest-carts/{cartId}/payment-methods',
        UserRoles::CUSTOMER => '/V1/carts/mine/payment-methods',
    ];
}

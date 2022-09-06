<?php declare(strict_types=1);

namespace CartTotals\Api;

use Core\Rest\Api\Data\UserRoles;

interface RestApiInterface
{
    const TOTALS = [
        UserRoles::GUEST    => '/V1/guest-carts/{cartId}/totals',
        UserRoles::CUSTOMER => '/V1/carts/mine/totals',
    ];
}

<?php declare(strict_types=1);

namespace Core\Rest\Provider;

use Core\Rest\Api\Data\UserRoleProviderInterface;
use Core\Rest\Api\Data\UserRoles;

class UserRoleProvider implements UserRoleProviderInterface
{
    public function getUserRole(string $token = null): string
    {
        if ($token) {
            return UserRoles::CUSTOMER;
        }

        return UserRoles::GUEST;
    }
}

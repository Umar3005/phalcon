<?php declare(strict_types=1);

namespace Core\Rest\Api\Data;

interface UserRoleProviderInterface
{
    public function getUserRole(string $token = null): string;
}

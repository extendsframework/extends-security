<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization;

use ExtendsFramework\Security\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Security\Authorization\Role\RoleInterface;

interface AuthorizationInfoInterface
{
    /**
     * Get authorization permissions.
     *
     * @return PermissionInterface[]
     */
    public function getPermissions(): array;

    /**
     * Get authorization roles.
     *
     * @return RoleInterface[]
     */
    public function getRoles(): array;
}

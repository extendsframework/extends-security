<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization;

use ExtendsFramework\Security\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Security\Authorization\Role\RoleInterface;
use ExtendsFramework\Security\Identity\IdentityInterface;

interface AuthorizerInterface
{
    /**
     * Verify if $identity is permitted for $permission.
     *
     * @param IdentityInterface   $identity
     * @param PermissionInterface $permission
     * @return bool
     */
    public function isPermitted(IdentityInterface $identity, PermissionInterface $permission): bool;

    /**
     * Assume that $identity is permitted for $permission.
     *
     * When $identity is not permitted for $permission, and exception will be thrown.
     *
     * @param IdentityInterface   $identity
     * @param PermissionInterface $permission
     * @return AuthorizerInterface
     * @throws AuthorizationException
     */
    public function checkPermission(IdentityInterface $identity, PermissionInterface $permission): AuthorizerInterface;

    /**
     * Verify if $identity has $role.
     *
     * @param IdentityInterface $identity
     * @param RoleInterface     $role
     * @return bool
     */
    public function hasRole(IdentityInterface $identity, RoleInterface $role): bool;

    /**
     * Assume that $identity has $role.
     *
     * When $identity does not contains $role, and exception will be thrown.
     *
     * @param IdentityInterface $identity
     * @param RoleInterface     $role
     * @return AuthorizerInterface
     * @throws AuthorizationException
     */
    public function checkRole(IdentityInterface $identity, RoleInterface $role): AuthorizerInterface;
}

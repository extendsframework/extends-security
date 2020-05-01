<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Authentication\AuthenticationException;
use ExtendsFramework\Authentication\Header\HeaderInterface;
use ExtendsFramework\Authorization\AuthorizationException;
use ExtendsFramework\Identity\IdentityInterface;

interface SecurityServiceInterface
{
    /**
     * Authenticate header.
     *
     * When authentication fails, an exception will be thrown.
     *
     * @param HeaderInterface $header
     *
     * @return SecurityServiceInterface
     * @throws AuthenticationException
     */
    public function authenticate(HeaderInterface $header): SecurityServiceInterface;

    /**
     * Get identity.
     *
     * An exception will be thrown when no identity is available.
     *
     * @return IdentityInterface
     * @throws AuthenticationException
     */
    public function getIdentity(): IdentityInterface;

    /**
     * If identity is permitted for $permission.
     *
     * An exception will be thrown when no identity is available.
     *
     * @param string $permission
     *
     * @return bool
     * @throws AuthenticationException
     */
    public function isPermitted(string $permission): bool;

    /**
     * If identity is permitted for $permission.
     *
     * An exception will be thrown when no identity is available or identity is not permitted for $permission.
     *
     * @param string $permission
     *
     * @return SecurityServiceInterface
     * @throws AuthorizationException
     * @throws AuthenticationException
     */
    public function checkPermission(string $permission): SecurityServiceInterface;

    /**
     * If identity has $role.
     *
     * An exception will be thrown when no identity is available.
     *
     * @param string $role
     *
     * @return bool
     * @throws AuthenticationException
     */
    public function hasRole(string $role): bool;

    /**
     * If identity has $role.
     *
     * An exception will be thrown when no identity is available or identity does not have $role assigned.
     *
     * @param string $role
     *
     * @return SecurityServiceInterface
     * @throws AuthorizationException
     * @throws AuthenticationException
     */
    public function checkRole(string $role): SecurityServiceInterface;
}

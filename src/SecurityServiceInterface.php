<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Authentication\Header\HeaderInterface;
use ExtendsFramework\Identity\IdentityInterface;

interface SecurityServiceInterface
{
    /**
     * Authenticate header.
     *
     * When authentication fails, false will be returned.
     *
     * @param HeaderInterface $header
     *
     * @return bool
     */
    public function authenticate(HeaderInterface $header): bool;

    /**
     * Get identity.
     *
     * An exception will be thrown when no identity is available.
     *
     * @return IdentityInterface
     */
    public function getIdentity(): ?IdentityInterface;

    /**
     * If identity is permitted for permission.
     *
     * An exception will be thrown when no identity is available.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function isPermitted(string $permission): bool;

    /**
     * If identity has role.
     *
     * An exception will be thrown when no identity is available.
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool;
}

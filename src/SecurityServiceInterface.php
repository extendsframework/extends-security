<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Authentication\Header\HeaderInterface;
use ExtendsFramework\Authorization\Policy\PolicyInterface;
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
     * @return IdentityInterface
     */
    public function getIdentity(): ?IdentityInterface;

    /**
     * If identity is permitted for permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function isPermitted(string $permission): bool;

    /**
     * If identity has role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool;

    /**
     * If policy is allowed by policy.
     *
     * @param PolicyInterface $policy
     *
     * @return bool
     */
    public function isAllowed(PolicyInterface $policy): bool;
}

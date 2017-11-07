<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Role;

interface RoleInterface
{
    /**
     * Get role name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Verify that $role is equal.
     *
     * Role is considered equal when names are exact the same, case sensitive.
     *
     * @param RoleInterface $role
     * @return bool
     */
    public function isEqual(RoleInterface $role): bool;
}

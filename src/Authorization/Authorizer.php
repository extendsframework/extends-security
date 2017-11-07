<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization;

use ExtendsFramework\Security\Authorization\Exception\IdentityNotAssignedToRole;
use ExtendsFramework\Security\Authorization\Exception\IdentityNotPermitted;
use ExtendsFramework\Security\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Security\Authorization\Realm\RealmInterface;
use ExtendsFramework\Security\Authorization\Role\RoleInterface;
use ExtendsFramework\Security\Identity\IdentityInterface;

class Authorizer implements AuthorizerInterface
{
    /**
     * Realms to get authorization information from.
     *
     * @var RealmInterface[]
     */
    protected $realms = [];

    /**
     * @inheritDoc
     */
    public function isPermitted(IdentityInterface $identity, PermissionInterface $permission): bool
    {
        $info = $this->getAuthorizationInfo($identity);
        foreach ($info->getPermissions() as $ownPermission) {
            if ($ownPermission->implies($permission) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function checkPermission(IdentityInterface $identity, PermissionInterface $permission): AuthorizerInterface
    {
        if ($this->isPermitted($identity, $permission) === false) {
            throw new IdentityNotPermitted();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasRole(IdentityInterface $identity, RoleInterface $role): bool
    {
        $info = $this->getAuthorizationInfo($identity);
        foreach ($info->getRoles() as $ownRole) {
            if ($ownRole->isEqual($role) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function checkRole(IdentityInterface $identity, RoleInterface $role): AuthorizerInterface
    {
        if ($this->hasRole($identity, $role) === false) {
            throw new IdentityNotAssignedToRole();
        }

        return $this;
    }

    /**
     * Add $realm to authorizer.
     *
     * @param RealmInterface $realm
     * @return Authorizer
     */
    public function addRealm(RealmInterface $realm): Authorizer
    {
        $this->realms[] = $realm;

        return $this;
    }

    /**
     * Get authorization information for $identity.
     *
     * When no authorization information can be found, an empty instance will be returned.
     *
     * @param IdentityInterface $identity
     * @return AuthorizationInfoInterface
     */
    protected function getAuthorizationInfo(IdentityInterface $identity): AuthorizationInfoInterface
    {
        foreach ($this->realms as $realm) {
            $info = $realm->getAuthorizationInfo($identity);
            if ($info instanceof AuthorizationInfoInterface) {
                return $info;
            }
        }

        return new AuthorizationInfo();
    }
}

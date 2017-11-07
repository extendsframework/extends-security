<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization;

use ExtendsFramework\Security\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Security\Authorization\Role\RoleInterface;
use PHPUnit\Framework\TestCase;

class AuthorizationInfoTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that correct values will be returned.
     *
     * @covers \ExtendsFramework\Security\Authorization\AuthorizationInfo::addPermission()
     * @covers \ExtendsFramework\Security\Authorization\AuthorizationInfo::addRole()
     * @covers \ExtendsFramework\Security\Authorization\AuthorizationInfo::getPermissions()
     * @covers \ExtendsFramework\Security\Authorization\AuthorizationInfo::getRoles()
     */
    public function testGetMethods(): void
    {
        $permission = $this->createMock(PermissionInterface::class);

        $role = $this->createMock(RoleInterface::class);

        /**
         * @var PermissionInterface $permission
         * @var RoleInterface       $role
         */
        $info = new AuthorizationInfo();
        $info
            ->addPermission($permission)
            ->addPermission($permission)
            ->addRole($role)
            ->addRole($role);

        $this->assertSame([
            $permission,
            $permission,
        ], $info->getPermissions());
        $this->assertSame([
            $role,
            $role,
        ], $info->getRoles());
    }
}

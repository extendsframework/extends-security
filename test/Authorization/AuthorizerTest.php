<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization;

use ExtendsFramework\Security\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Security\Authorization\Realm\RealmInterface;
use ExtendsFramework\Security\Authorization\Role\RoleInterface;
use ExtendsFramework\Security\Identity\IdentityInterface;
use PHPUnit\Framework\TestCase;

class AuthorizerTest extends TestCase
{
    /**
     * Is permitted.
     *
     * Test that permission is permitted for identity.
     *
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::addRealm()
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::getAuthorizationInfo()
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::isPermitted()
     */
    public function testIsPermitted(): void
    {
        $permission = $this->createMock(PermissionInterface::class);
        $permission
            ->expects($this->once())
            ->method('implies')
            ->with($permission)
            ->willReturn(true);

        $info = $this->createMock(AuthorizationInfoInterface::class);
        $info
            ->expects($this->once())
            ->method('getPermissions')
            ->willReturn([
                $permission
            ]);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('getAuthorizationInfo')
            ->with($identity)
            ->willReturn($info);

        /**
         * @var RealmInterface      $realm
         * @var IdentityInterface   $identity
         * @var PermissionInterface $permission
         */
        $authorizer = new Authorizer();
        $permitted = $authorizer
            ->addRealm($realm)
            ->isPermitted($identity, $permission);

        $this->assertTrue($permitted);
    }

    /**
     * Check permission
     *
     * Test that permission is permitted for identity and an exception will be thrown.
     *
     * @covers                   \ExtendsFramework\Security\Authorization\Authorizer::addRealm()
     * @covers                   \ExtendsFramework\Security\Authorization\Authorizer::getAuthorizationInfo()
     * @covers                   \ExtendsFramework\Security\Authorization\Authorizer::isPermitted()
     * @covers                   \ExtendsFramework\Security\Authorization\Authorizer::checkPermission()
     * @covers                   \ExtendsFramework\Security\Authorization\Exception\IdentityNotPermitted::__construct
     * @expectedException        \ExtendsFramework\Security\Authorization\Exception\IdentityNotPermitted
     * @expectedExceptionMessage Identity is not permitted by permission.
     */
    public function testCheckPermission(): void
    {
        $permission = $this->createMock(PermissionInterface::class);
        $permission
            ->expects($this->exactly(2))
            ->method('implies')
            ->with($permission)
            ->willReturnOnConsecutiveCalls(
                true,
                false
            );

        $info = $this->createMock(AuthorizationInfoInterface::class);
        $info
            ->expects($this->exactly(2))
            ->method('getPermissions')
            ->willReturn([
                $permission
            ]);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->exactly(2))
            ->method('getAuthorizationInfo')
            ->with($identity)
            ->willReturn($info);

        /**
         * @var RealmInterface      $realm
         * @var IdentityInterface   $identity
         * @var PermissionInterface $permission
         */
        $authorizer = new Authorizer();
        $authorizer
            ->addRealm($realm)
            ->checkPermission($identity, $permission)
            ->checkPermission($identity, $permission);
    }

    /**
     * Has role.
     *
     * Test that identity has role.
     *
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::addRealm()
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::getAuthorizationInfo()
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::hasRole()
     */
    public function testHasRole(): void
    {
        $role = $this->createMock(RoleInterface::class);
        $role
            ->expects($this->once())
            ->method('isEqual')
            ->with($role)
            ->willReturn(true);

        $info = $this->createMock(AuthorizationInfoInterface::class);
        $info
            ->expects($this->once())
            ->method('getRoles')
            ->willReturn([
                $role
            ]);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('getAuthorizationInfo')
            ->with($identity)
            ->willReturn($info);

        /**
         * @var RealmInterface    $realm
         * @var IdentityInterface $identity
         * @var RoleInterface     $role
         */
        $authorizer = new Authorizer();
        $hasRole = $authorizer
            ->addRealm($realm)
            ->hasRole($identity, $role);

        $this->assertTrue($hasRole);
    }

    /**
     * Check role.
     *
     * Test that identity does not have role and an exception will be thrown.
     *
     * @covers                   \ExtendsFramework\Security\Authorization\Authorizer::addRealm()
     * @covers                   \ExtendsFramework\Security\Authorization\Authorizer::getAuthorizationInfo()
     * @covers                   \ExtendsFramework\Security\Authorization\Authorizer::hasRole()
     * @covers                   \ExtendsFramework\Security\Authorization\Authorizer::checkRole()
     * @covers                   \ExtendsFramework\Security\Authorization\Exception\IdentityNotAssignedToRole::__construct
     * @expectedException        \ExtendsFramework\Security\Authorization\Exception\IdentityNotAssignedToRole
     * @expectedExceptionMessage Identity is not assigned to role.
     */
    public function testCheckRole(): void
    {
        $role = $this->createMock(RoleInterface::class);
        $role
            ->expects($this->exactly(2))
            ->method('isEqual')
            ->with($role)
            ->willReturnOnConsecutiveCalls(
                true,
                false
            );

        $info = $this->createMock(AuthorizationInfoInterface::class);
        $info
            ->expects($this->exactly(2))
            ->method('getRoles')
            ->willReturn([
                $role
            ]);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->exactly(2))
            ->method('getAuthorizationInfo')
            ->with($identity)
            ->willReturn($info);

        /**
         * @var RealmInterface    $realm
         * @var IdentityInterface $identity
         * @var RoleInterface     $role
         */
        $authorizer = new Authorizer();
        $authorizer
            ->addRealm($realm)
            ->checkRole($identity, $role)
            ->checkRole($identity, $role);
    }

    /**
     * No authorization info.
     *
     * Test that an empty authorization info instance will be used when none available.
     *
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::addRealm()
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::getAuthorizationInfo()
     * @covers \ExtendsFramework\Security\Authorization\Authorizer::hasRole()
     */
    public function testNoAuthorizationInfo(): void
    {
        $role = $this->createMock(RoleInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        /**
         * @var RealmInterface    $realm
         * @var IdentityInterface $identity
         * @var RoleInterface     $role
         */
        $authorizer = new Authorizer();

        $this->assertFalse($authorizer->hasRole($identity, $role));
    }
}

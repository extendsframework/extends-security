<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Authentication\AuthenticationInfoInterface;
use ExtendsFramework\Authentication\AuthenticatorInterface;
use ExtendsFramework\Authentication\Token\TokenInterface;
use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;
use ExtendsFramework\Identity\IdentityInterface;
use ExtendsFramework\Identity\Storage\StorageInterface;
use ExtendsFramework\Security\Exception\IdentityNotFound;
use PHPUnit\Framework\TestCase;

class SecurityServiceTest extends TestCase
{
    /**
     * Authenticate.
     *
     * Test that authenticator will authenticate given token.
     *
     * @covers \ExtendsFramework\Security\SecurityService::__construct()
     * @covers \ExtendsFramework\Security\SecurityService::authenticate()
     * @covers \ExtendsFramework\Security\SecurityService::getIdentity()
     */
    public function testAuthenticate(): void
    {
        $token = $this->createMock(TokenInterface::class);

        $info = $this->createMock(AuthenticationInfoInterface::class);
        $info
            ->expects($this->once())
            ->method('getIdentifier')
            ->willReturn('foo-bar-baz');

        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $authenticator
            ->expects($this->once())
            ->method('authenticate')
            ->with($token)
            ->willReturn($info);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $storage = $this->createMock(StorageInterface::class);
        $storage
            ->expects($this->once())
            ->method('setIdentity')
            ->with($this->isInstanceOf(IdentityInterface::class));

        $storage
            ->expects($this->once())
            ->method('getIdentity')
            ->willReturn($identity);

        /**
         * @var AuthenticatorInterface $authenticator
         * @var AuthorizerInterface    $authorizer
         * @var StorageInterface       $storage
         * @var TokenInterface         $token
         */
        $service = new SecurityService($authenticator, $authorizer, $storage);

        $this->assertSame($service, $service->authenticate($token));
        $this->assertInstanceOf(IdentityInterface::class, $service->getIdentity());
    }

    /**
     * Authorizer methods.
     *
     * Test that correct authorizer methods will be called.
     *
     * @covers \ExtendsFramework\Security\SecurityService::__construct()
     * @covers \ExtendsFramework\Security\SecurityService::isPermitted()
     * @covers \ExtendsFramework\Security\SecurityService::checkPermission()
     * @covers \ExtendsFramework\Security\SecurityService::hasRole()
     * @covers \ExtendsFramework\Security\SecurityService::checkRole()
     */
    public function testAuthorizerMethods(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $storage = $this->createMock(StorageInterface::class);
        $storage
            ->expects($this->exactly(4))
            ->method('getIdentity')
            ->willReturn($identity);

        $authorizer = $this->createMock(AuthorizerInterface::class);
        $authorizer
            ->expects($this->once())
            ->method('isPermitted')
            ->with(
                $identity,
                $this->isInstanceOf(PermissionInterface::class)
            )
            ->willReturn(true);

        $authorizer
            ->expects($this->once())
            ->method('checkPermission')
            ->with(
                $identity,
                $this->isInstanceOf(PermissionInterface::class)
            );

        $authorizer
            ->expects($this->once())
            ->method('hasRole')
            ->with(
                $identity,
                $this->isInstanceOf(RoleInterface::class)
            )
            ->willReturn(true);

        $authorizer
            ->expects($this->once())
            ->method('checkRole')
            ->with(
                $identity,
                $this->isInstanceOf(RoleInterface::class)
            );

        /**
         * @var AuthenticatorInterface $authenticator
         * @var AuthorizerInterface    $authorizer
         * @var StorageInterface       $storage
         * @var TokenInterface         $token
         */
        $service = new SecurityService($authenticator, $authorizer, $storage);

        $this->assertTrue($service->isPermitted('foo:bar:baz'));
        $this->assertSame($service, $service->checkPermission('foo:bar:baz'));
        $this->assertTrue($service->hasRole('administraotr'));
        $this->assertSame($service, $service->checkRole('administrator'));
    }

    /**
     * Identity not found.
     *
     * Test that an exception will be thrown if identity is not found.
     *
     * @covers                   \ExtendsFramework\Security\SecurityService::__construct()
     * @covers                   \ExtendsFramework\Security\SecurityService::getIdentity()
     * @covers                   \ExtendsFramework\Security\Exception\IdentityNotFound::__construct()
     * @expectedException        \ExtendsFramework\Security\Exception\IdentityNotFound
     * @expectedExceptionMessage No identity found. Please authenticate first.
     */
    public function testIdentityNotFound(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $storage = $this->createMock(StorageInterface::class);

        /**
         * @var AuthenticatorInterface $authenticator
         * @var AuthorizerInterface    $authorizer
         * @var StorageInterface       $storage
         * @var TokenInterface         $token
         */
        $service = new SecurityService($authenticator, $authorizer, $storage);
        $service->getIdentity();
    }
}

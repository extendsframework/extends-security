<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Authentication\AuthenticationInfoInterface;
use ExtendsFramework\Authentication\AuthenticatorInterface;
use ExtendsFramework\Authentication\Header\HeaderInterface;
use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Policy\PolicyInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;
use ExtendsFramework\Identity\IdentityInterface;
use ExtendsFramework\Identity\Storage\StorageInterface;
use PHPUnit\Framework\TestCase;

class SecurityServiceTest extends TestCase
{
    /**
     * Authenticate.
     *
     * Test that authenticator will authenticate given header.
     *
     * @covers \ExtendsFramework\Security\SecurityService::__construct()
     * @covers \ExtendsFramework\Security\SecurityService::authenticate()
     * @covers \ExtendsFramework\Security\SecurityService::getIdentity()
     */
    public function testAuthenticate(): void
    {
        $header = $this->createMock(HeaderInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $info = $this->createMock(AuthenticationInfoInterface::class);
        $info
            ->expects($this->once())
            ->method('getIdentity')
            ->willReturn($identity);

        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $authenticator
            ->expects($this->exactly(2))
            ->method('authenticate')
            ->with($header)
            ->willReturnOnConsecutiveCalls($info, null);

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
         * @var AuthorizerInterface $authorizer
         * @var StorageInterface $storage
         * @var HeaderInterface $header
         */
        $service = new SecurityService($authenticator, $authorizer, $storage);

        $this->assertTrue($service->authenticate($header));
        $this->assertIsObject($service->getIdentity());
        $this->assertFalse($service->authenticate($header));
    }

    /**
     * Authorizer methods.
     *
     * Test that correct authorizer methods will be called.
     *
     * @covers \ExtendsFramework\Security\SecurityService::__construct()
     * @covers \ExtendsFramework\Security\SecurityService::isPermitted()
     * @covers \ExtendsFramework\Security\SecurityService::hasRole()
     * @covers \ExtendsFramework\Security\SecurityService::isAllowed()
     */
    public function testAuthorizerMethods(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $policy = $this->createMock(PolicyInterface::class);

        $storage = $this->createMock(StorageInterface::class);
        $storage
            ->expects($this->exactly(3))
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
            ->method('hasRole')
            ->with(
                $identity,
                $this->isInstanceOf(RoleInterface::class)
            )
            ->willReturn(true);

        $authorizer
            ->expects($this->once())
            ->method('isAllowed')
            ->with($identity, $policy)
            ->willReturn(true);

        /**
         * @var AuthenticatorInterface $authenticator
         * @var AuthorizerInterface $authorizer
         * @var StorageInterface $storage
         * @var HeaderInterface $header
         * @var  PolicyInterface $policy
         */
        $service = new SecurityService($authenticator, $authorizer, $storage);

        $this->assertTrue($service->isPermitted('foo:bar:baz'));
        $this->assertTrue($service->hasRole('administrator'));
        $this->assertTrue($service->isAllowed($policy));
    }

    /**
     * Identity not found.
     *
     * Test that null will be returned when identity is not found.
     *
     * @covers \ExtendsFramework\Security\SecurityService::__construct()
     * @covers \ExtendsFramework\Security\SecurityService::getIdentity()
     * @covers \ExtendsFramework\Security\SecurityService::isPermitted()
     * @covers \ExtendsFramework\Security\SecurityService::hasRole()
     * @covers \ExtendsFramework\Security\SecurityService::isAllowed()
     */
    public function testIdentityNotFound(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $storage = $this->createMock(StorageInterface::class);

        $policy = $this->createMock(PolicyInterface::class);

        /**
         * @var AuthenticatorInterface $authenticator
         * @var AuthorizerInterface $authorizer
         * @var StorageInterface $storage
         * @var HeaderInterface $header
         * @var  PolicyInterface $policy
         */
        $service = new SecurityService($authenticator, $authorizer, $storage);

        $this->assertNull($service->getIdentity());
        $this->assertFalse($service->isPermitted('foo:bar:baz'));
        $this->assertFalse($service->hasRole('administrator'));
        $this->assertFalse($service->isAllowed($policy));
    }
}

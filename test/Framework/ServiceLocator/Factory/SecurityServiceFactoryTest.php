<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ServiceLocator\Factory;

use ExtendsFramework\Security\Authentication\AuthenticatorInterface;
use ExtendsFramework\Security\Authorization\AuthorizerInterface;
use ExtendsFramework\Security\Identity\Storage\StorageInterface;
use ExtendsFramework\Security\SecurityServiceInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class SecurityServiceFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that instance of SecurityServiceInterface will be created.
     *
     * @covers \ExtendsFramework\Security\Framework\ServiceLocator\Factory\SecurityServiceFactory::createService()
     * @covers \ExtendsFramework\Security\Framework\ServiceLocator\Factory\SecurityServiceFactory::getAuthorizer()
     * @covers \ExtendsFramework\Security\Framework\ServiceLocator\Factory\SecurityServiceFactory::getAuthenticator()
     * @covers \ExtendsFramework\Security\Framework\ServiceLocator\Factory\SecurityServiceFactory::getStorage()
     */
    public function testCreateService(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->exactly(3))
            ->method('getService')
            ->withConsecutive(
                [AuthenticatorInterface::class],
                [AuthorizerInterface::class],
                [StorageInterface::class]
            )
            ->willReturnOnConsecutiveCalls(
                $this->createMock(AuthenticatorInterface::class),
                $this->createMock(AuthorizerInterface::class),
                $this->createMock(StorageInterface::class)
            );

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new SecurityServiceFactory();
        $security = $factory->createService(SecurityServiceInterface::class, $serviceLocator);

        $this->assertInstanceOf(SecurityServiceInterface::class, $security);
    }
}

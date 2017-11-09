<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ServiceLocator\Factory;

use ExtendsFramework\Security\Authentication\AuthenticatorInterface;
use ExtendsFramework\Security\Authorization\AuthorizerInterface;
use ExtendsFramework\Security\Identity\Storage\StorageInterface;
use ExtendsFramework\Security\SecurityService;
use ExtendsFramework\Security\SecurityServiceInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class SecurityServiceFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): SecurityServiceInterface
    {
        return new SecurityService(
            $this->getAuthenticator($serviceLocator),
            $this->getAuthorizer($serviceLocator),
            $this->getStorage($serviceLocator)
        );
    }

    /**
     * Get authenticator from $serviceLocator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticatorInterface
     * @throws ServiceLocatorException
     */
    protected function getAuthenticator(ServiceLocatorInterface $serviceLocator): AuthenticatorInterface
    {
        return $serviceLocator->getService(AuthenticatorInterface::class);
    }

    /**
     * Get authorizer from $serviceLocator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthorizerInterface
     * @throws ServiceLocatorException
     */
    protected function getAuthorizer(ServiceLocatorInterface $serviceLocator): AuthorizerInterface
    {
        return $serviceLocator->getService(AuthorizerInterface::class);
    }

    /**
     * Get identity storage from $serviceLocator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return StorageInterface
     * @throws ServiceLocatorException
     */
    protected function getStorage(ServiceLocatorInterface $serviceLocator): StorageInterface
    {
        return $serviceLocator->getService(StorageInterface::class);
    }
}

<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ServiceLocator\Factory;

use ExtendsFramework\Security\Authorization\Authorizer;
use ExtendsFramework\Security\Authorization\AuthorizerInterface;
use ExtendsFramework\Security\Authorization\Realm\RealmInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class AuthorizerFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): AuthorizerInterface
    {
        $config = $serviceLocator->getConfig();
        $config = $config[AuthorizerInterface::class] ?? [];

        $authenticator = new Authorizer();
        foreach ($config['realms'] ?? [] as $config) {
            $authenticator->addRealm(
                $this->createRealm($serviceLocator, $config)
            );
        }

        return $authenticator;
    }

    /**
     * Get authentication from $serviceLocator for $config.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param array                   $config
     * @return RealmInterface
     * @throws ServiceLocatorException
     */
    protected function createRealm(ServiceLocatorInterface $serviceLocator, array $config): RealmInterface
    {
        return $serviceLocator->getService($config['name'], $config['options'] ?? []);
    }
}

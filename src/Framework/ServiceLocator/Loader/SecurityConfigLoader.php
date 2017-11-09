<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ServiceLocator\Loader;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Security\Authentication\AuthenticatorInterface;
use ExtendsFramework\Security\Authorization\AuthorizerInterface;
use ExtendsFramework\Security\Framework\Http\Middleware\NotAuthenticatedMiddleware;
use ExtendsFramework\Security\Framework\Http\Middleware\NotAuthorizedMiddleware;
use ExtendsFramework\Security\Framework\Http\Middleware\RouterAuthorizationMiddleware;
use ExtendsFramework\Security\Framework\ServiceLocator\Factory\AuthenticatorFactory;
use ExtendsFramework\Security\Framework\ServiceLocator\Factory\AuthorizerFactory;
use ExtendsFramework\Security\Framework\ServiceLocator\Factory\StorageFactory;
use ExtendsFramework\Security\Identity\Storage\StorageInterface;
use ExtendsFramework\Security\SecurityService;
use ExtendsFramework\Security\SecurityServiceInterface;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class SecurityConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    AuthenticatorInterface::class => AuthenticatorFactory::class,
                    AuthorizerInterface::class => AuthorizerFactory::class,
                    StorageInterface::class => StorageFactory::class
                ],
                ReflectionResolver::class => [
                    SecurityServiceInterface::class => SecurityService::class
                ],
            ],
            MiddlewareChainInterface::class => [
                NotAuthorizedMiddleware::class => 140,
                NotAuthenticatedMiddleware::class => 130,
                RouterAuthorizationMiddleware::class => 120,
            ],
            AuthenticatorInterface::class => [
                'realms' => [],
            ],
            AuthorizerInterface::class => [
                'realms' => [],
            ]
        ];
    }
}

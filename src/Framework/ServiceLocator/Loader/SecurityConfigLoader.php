<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ServiceLocator\Loader;

use ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsFramework\Security\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsFramework\Security\SecurityService;
use ExtendsFramework\Security\SecurityServiceInterface;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
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
                ReflectionResolver::class => [
                    AuthorizationMiddleware::class => AuthorizationMiddleware::class,
                    AuthenticationMiddleware::class => AuthenticationMiddleware::class,
                    SecurityServiceInterface::class => SecurityService::class,
                ],
            ],
        ];
    }
}

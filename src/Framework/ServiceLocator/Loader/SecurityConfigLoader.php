<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ServiceLocator\Loader;

use ExtendsFramework\Security\Framework\Http\Middleware\RouterAuthorizationMiddleware;
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
                    SecurityServiceInterface::class => SecurityService::class,
                    RouterAuthorizationMiddleware::class => RouterAuthorizationMiddleware::class,
                ],
            ],
        ];
    }
}

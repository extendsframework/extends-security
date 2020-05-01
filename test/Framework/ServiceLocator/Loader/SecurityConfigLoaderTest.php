<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ServiceLocator\Loader;

use ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsFramework\Security\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsFramework\Security\SecurityService;
use ExtendsFramework\Security\SecurityServiceInterface;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class SecurityConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsFramework\Security\Framework\ServiceLocator\Loader\SecurityConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new SecurityConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                ReflectionResolver::class => [
                    AuthorizationMiddleware::class => AuthorizationMiddleware::class,
                    AuthenticationMiddleware::class => AuthenticationMiddleware::class,
                    SecurityServiceInterface::class => SecurityService::class,
                ],
            ],
        ], $loader->load());
    }
}

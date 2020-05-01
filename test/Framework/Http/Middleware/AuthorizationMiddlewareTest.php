<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Router\Route\RouteMatchInterface;
use ExtendsFramework\Security\SecurityServiceInterface;
use PHPUnit\Framework\TestCase;

class AuthorizationMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that permissions and roles route match parameters will be used for authorization.
     *
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\AuthorizationMiddleware::__construct()
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\AuthorizationMiddleware::process()
     */
    public function testProcess(): void
    {
        $security = $this->createMock(SecurityServiceInterface::class);
        $security
            ->expects($this->once())
            ->method('checkPermission')
            ->with('foo:bar:baz');

        $security
            ->expects($this->once())
            ->method('checkRole')
            ->with('administrator');

        $routeMatch = $this->createMock(RouteMatchInterface::class);
        $routeMatch
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'permissions' => [
                    'foo:bar:baz',
                ],
                'roles' => [
                    'administrator',
                ],
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routeMatch')
            ->willReturn($routeMatch);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($this->createMock(ResponseInterface::class));

        /**
         * @var SecurityServiceInterface $security
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthorizationMiddleware($security);
        $response = $middleware->process($request, $chain);

        $this->assertIsObject($response);
    }
}

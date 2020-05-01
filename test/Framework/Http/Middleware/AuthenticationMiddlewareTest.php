<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Authentication\Header\HeaderInterface;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Security\Framework\Http\Middleware\Exception\InvalidHeaderFormat;
use ExtendsFramework\Security\SecurityServiceInterface;
use PHPUnit\Framework\TestCase;

class AuthenticationMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that permissions and roles route match parameters will be used for authorization.
     *
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware::__construct()
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware::process()
     */
    public function testProcess(): void
    {
        $security = $this->createMock(SecurityServiceInterface::class);
        $security
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->callback(function (HeaderInterface $header) {
                $this->assertSame('Bearer', $header->getScheme());
                $this->assertSame('ed6ed1ec-769b-4f35-b74a-d4d4205f1d88', $header->getCredentials());

                return true;
            }));

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn('Bearer ed6ed1ec-769b-4f35-b74a-d4d4205f1d88');

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($this->createMock(ResponseInterface::class));

        /**
         * @var SecurityServiceInterface $security
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthenticationMiddleware($security);
        $response = $middleware->process($request, $chain);

        $this->assertIsObject($response);
    }

    /**
     * Invalid header format.
     *
     * Test that an exception will be thrown when the Authorization header has a invalid format.
     *
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware::__construct()
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware::process()
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\Exception\InvalidHeaderFormat::__construct()
     */
    public function testInvalidHeaderFormat(): void
    {
        $this->expectException(InvalidHeaderFormat::class);
        $this->expectExceptionMessage('Invalid Authorization header format.');

        $security = $this->createMock(SecurityServiceInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn('Bearer');

        $chain = $this->createMock(MiddlewareChainInterface::class);

        /**
         * @var SecurityServiceInterface $security
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthenticationMiddleware($security);
        $middleware->process($request, $chain);
    }
}

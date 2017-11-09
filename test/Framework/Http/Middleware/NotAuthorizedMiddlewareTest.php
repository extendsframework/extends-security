<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Security\Authorization\AuthorizationException;
use LogicException;
use PHPUnit\Framework\TestCase;

class NotAuthorizedMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that authorization exception will be caught and a correct response will be returned.
     *
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\NotAuthorizedMiddleware::process()
     */
    public function testProcess(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willThrowException(new AuthorizationExceptionStub('Not authorized.'));

        /**
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new NotAuthorizedMiddleware();
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        if ($response instanceof ResponseInterface) {
            $this->assertSame(403, $response->getStatusCode());
            $this->assertSame([
                'message' => 'Not authorized.',
            ], $response->getBody());
        }
    }
}

class AuthorizationExceptionStub extends LogicException implements AuthorizationException
{
}

<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Logger\LoggerInterface;
use ExtendsFramework\Logger\Priority\PriorityInterface;
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
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                'Request authorization failed, got message "Not authorized.".',
                $this->isInstanceOf(PriorityInterface::class)
            );

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
         * @var LoggerInterface          $logger
         */
        $middleware = new NotAuthorizedMiddleware($logger);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        if ($response instanceof ResponseInterface) {
            $this->assertSame(403, $response->getStatusCode());
        }
    }
}

class AuthorizationExceptionStub extends LogicException implements AuthorizationException
{
}

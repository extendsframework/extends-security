<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Logger\LoggerInterface;
use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\Security\Authentication\AuthenticationException;
use LogicException;
use PHPUnit\Framework\TestCase;

class NotAuthenticatedMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that authentication exception will be caught and a correct response will be returned.
     *
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\NotAuthenticatedMiddleware::process()
     */
    public function testProcess(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                'Request authentication failed, got message "Invalid credentials.".',
                $this->isInstanceOf(PriorityInterface::class)
            );

        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willThrowException(new AuthenticationExceptionStub('Invalid credentials.'));

        /**
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         * @var LoggerInterface          $logger
         */
        $middleware = new NotAuthenticatedMiddleware($logger);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        if ($response instanceof ResponseInterface) {
            $this->assertSame(401, $response->getStatusCode());
        }
    }
}

class AuthenticationExceptionStub extends LogicException implements AuthenticationException
{
}

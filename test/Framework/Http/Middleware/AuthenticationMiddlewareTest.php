<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Security\Authentication\Token\TokenInterface;
use ExtendsFramework\Security\Identity\IdentityInterface;
use ExtendsFramework\Security\SecurityServiceInterface;
use PHPUnit\Framework\TestCase;

class AuthenticationMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that
     *
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware::__construct
     * @covers \ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware::process()
     */
    public function testProcess(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $token = $this->createMock(TokenInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $security = $this->createMock(SecurityServiceInterface::class);
        $security
            ->expects($this->once())
            ->method('authenticate')
            ->with($token)
            ->willReturnSelf();

        $security
            ->expects($this->once())
            ->method('getIdentity')
            ->willReturn($identity);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('andAttribute')
            ->with('identity', $identity)
            ->willReturnSelf();

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        /**
         * @var SecurityServiceInterface $security
         * @var TokenInterface           $token
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthenticationMiddlewareStub($security, $token);

        $this->assertSame($response, $middleware->process($request, $chain));
    }
}

class AuthenticationMiddlewareStub extends AuthenticationMiddleware
{
    /**
     * @var TokenInterface
     */
    protected $token;

    /**
     * @param SecurityServiceInterface $securityService
     * @param TokenInterface           $token
     */
    public function __construct(SecurityServiceInterface $securityService, TokenInterface $token)
    {
        parent::__construct($securityService);

        $this->token = $token;
    }

    /**
     * @inheritDoc
     */
    protected function getToken(RequestInterface $request): TokenInterface
    {
        return $this->token;
    }
}
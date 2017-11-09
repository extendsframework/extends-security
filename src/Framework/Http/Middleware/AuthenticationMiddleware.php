<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Security\Authentication\Token\TokenInterface;
use ExtendsFramework\Security\SecurityServiceInterface;

abstract class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * Security service.
     *
     * @var SecurityServiceInterface
     */
    protected $security;

    /**
     * AuthenticationMiddleware constructor.
     *
     * @param SecurityServiceInterface $security
     */
    public function __construct(SecurityServiceInterface $security)
    {
        $this->security = $security;
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $this->security->authenticate(
            $this->getToken($request)
        );

        return $chain->proceed(
            $request->andAttribute('identity', $this->security->getIdentity())
        );
    }

    /**
     * Get authentication token.
     *
     * @param RequestInterface $request
     * @return TokenInterface
     */
    abstract protected function getToken(RequestInterface $request): TokenInterface;
}

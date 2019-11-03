<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Authentication\AuthenticationException;
use ExtendsFramework\Authentication\Token\TokenInterface;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Security\SecurityServiceInterface;

abstract class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * Security service.
     *
     * @var SecurityServiceInterface
     */
    private $securityService;

    /**
     * AuthenticationMiddleware constructor.
     *
     * @param SecurityServiceInterface $securityService
     */
    public function __construct(SecurityServiceInterface $securityService)
    {
        $this->securityService = $securityService;
    }

    /**
     * @inheritDoc
     * @throws AuthenticationException
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $this->securityService->authenticate($this->getToken($request));

        return $chain->proceed($request->andAttribute('identity', $this->securityService->getIdentity()));
    }

    /**
     * Get authentication token.
     *
     * @param RequestInterface $request
     * @return TokenInterface
     */
    abstract protected function getToken(RequestInterface $request): TokenInterface;
}

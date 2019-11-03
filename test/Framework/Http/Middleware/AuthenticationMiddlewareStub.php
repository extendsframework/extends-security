<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Authentication\Token\TokenInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Security\SecurityServiceInterface;

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

<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Authentication\AuthenticationException;
use ExtendsFramework\Authentication\Header\Header;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Security\Framework\Http\Middleware\Exception\InvalidHeaderFormat;
use ExtendsFramework\Security\SecurityServiceInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * Security service.
     *
     * @var SecurityServiceInterface
     */
    private $securityService;

    /**
     * Pattern to detect scheme and credentials.
     *
     * @var string
     */
    private $pattern = '/^(?P<scheme>[^\s]+)\s(?P<credentials>[^\s]+)$/';

    /**
     * AuthorizationHeaderMiddleware constructor.
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
        $authorization = $request->getHeader('Authorization');
        if ($authorization) {
            if (!is_string($authorization) || !preg_match($this->pattern, $authorization, $matches)) {
                throw new InvalidHeaderFormat();
            }

            $this->securityService->authenticate(new Header($matches['scheme'], $matches['credentials']));
        }

        return $chain->proceed($request);
    }
}

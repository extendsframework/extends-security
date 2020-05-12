<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Authentication\Header\Header;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Security\Framework\ProblemDetails\UnauthorizedProblemDetails;
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
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $authorization = $request->getHeader('Authorization');
        if ($authorization) {
            if (!is_string($authorization) || !preg_match($this->pattern, $authorization, $matches)
                || !$this->securityService->authenticate(new Header($matches['scheme'], $matches['credentials']))) {
                return (new Response())->withBody(
                    new UnauthorizedProblemDetails($request)
                );
            }
        }

        return $chain->proceed($request);
    }
}

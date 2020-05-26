<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Router\Route\RouteMatchInterface;
use ExtendsFramework\Security\Framework\ProblemDetails\ForbiddenProblemDetails;
use ExtendsFramework\Security\SecurityServiceInterface;

class AuthorizationMiddleware implements MiddlewareInterface
{
    /**
     * Security service.
     *
     * @var SecurityServiceInterface
     */
    private $securityService;

    /**
     * RoutePermissionMiddleware constructor.
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
        $match = $request->getAttribute('routeMatch');
        if ($match instanceof RouteMatchInterface) {
            $parameters = $match->getParameters();

            if (isset($parameters['permissions']) || isset($parameters['roles'])) {
                $authorized = false;

                foreach ($parameters['permissions'] ?? [] as $permission) {
                    if ($this->securityService->isPermitted($permission)) {
                        $authorized = true;
                    }
                }

                foreach ($parameters['roles'] ?? [] as $role) {
                    if ($this->securityService->hasRole($role)) {
                        $authorized = true;
                    }
                }

                if (!$authorized) {
                    return (new Response())->withBody(
                        new ForbiddenProblemDetails($request)
                    );
                }
            }
        }

        return $chain->proceed($request);
    }
}

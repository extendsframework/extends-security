<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Authentication\AuthenticationException;
use ExtendsFramework\Authorization\AuthorizationException;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Router\Route\RouteMatchInterface;
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
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $match = $request->getAttribute('routeMatch');
        if ($match instanceof RouteMatchInterface) {
            $parameters = $match->getParameters();

            foreach ($parameters['permissions'] ?? [] as $permission) {
                $this->securityService->checkPermission($permission);
            }

            foreach ($parameters['roles'] ?? [] as $role) {
                $this->securityService->checkRole($role);
            }
        }

        return $chain->proceed($request);
    }
}

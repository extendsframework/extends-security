<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Http\Router\Route\RouteMatchInterface;
use ExtendsFramework\Security\SecurityServiceInterface;

class RouterAuthorizationMiddleware implements MiddlewareInterface
{
    /**
     * Security service.
     *
     * @var SecurityServiceInterface
     */
    protected $securityService;

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

            foreach ($parameters['permissions'] ?? [] as $permission) {
                $this
                    ->getSecurityService()
                    ->checkPermission($permission);
            }

            foreach ($parameters['roles'] ?? [] as $role) {
                $this
                    ->getSecurityService()
                    ->checkRole($role);
            }
        }

        return $chain->proceed($request);
    }

    /**
     * Get security service.
     *
     * @return SecurityServiceInterface
     */
    public function getSecurityService(): SecurityServiceInterface
    {
        return $this->securityService;
    }
}

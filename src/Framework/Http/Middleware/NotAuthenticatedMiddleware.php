<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\Http\Response\Response;
use ExtendsFramework\Http\Response\ResponseInterface;
use ExtendsFramework\Logger\LoggerInterface;
use ExtendsFramework\Logger\Priority\Notice\NoticePriority;
use ExtendsFramework\Security\Authentication\AuthenticationException;

class NotAuthenticatedMiddleware implements MiddlewareInterface
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * NotAuthenticatedMiddleware constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        try {
            return $chain->proceed($request);
        } catch (AuthenticationException $exception) {
            $this->logger->log(sprintf(
                'Request authentication failed, got message "%s".',
                $exception->getMessage()
            ), new NoticePriority());

            return (new Response())->withStatusCode(401);
        }
    }
}

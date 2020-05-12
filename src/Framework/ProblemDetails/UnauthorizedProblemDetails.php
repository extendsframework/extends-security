<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ProblemDetails;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\ProblemDetails\ProblemDetails;

class UnauthorizedProblemDetails extends ProblemDetails
{
    /**
     * ForbiddenProblemDetails constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct(
            '/problems/authentication/unauthorized',
            'Unauthorized',
            'Failed to authenticate request.',
            401,
            $request->getUri()->toRelative()
        );
    }
}

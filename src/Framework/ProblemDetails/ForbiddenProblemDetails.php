<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\ProblemDetails;

use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\ProblemDetails\ProblemDetails;

class ForbiddenProblemDetails extends ProblemDetails
{
    /**
     * ForbiddenProblemDetails constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct(
            '/problems/authorization/forbidden',
            'Forbidden',
            'Failed to authorize request.',
            403,
            $request->getUri()->toRelative()
        );
    }
}

<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Framework\Http\Middleware\Exception;

use ExtendsFramework\Authentication\AuthenticationException;
use RuntimeException;

class InvalidHeaderFormat extends RuntimeException implements AuthenticationException
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct('Invalid Authorization header format.');
    }
}

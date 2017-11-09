<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Exception;

use ExtendsFramework\Security\Authentication\AuthenticationException;
use ExtendsFramework\Security\SecurityServiceException;
use LogicException;

class IdentityNotFound extends LogicException implements SecurityServiceException, AuthenticationException
{
    /**
     * SecurityServiceNotAuthenticated constructor.
     */
    public function __construct()
    {
        parent::__construct('No identity found. Please authenticate first.');
    }
}

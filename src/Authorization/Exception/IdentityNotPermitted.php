<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Exception;

use ExtendsFramework\Security\Authorization\AuthorizationException;
use LogicException;

class IdentityNotPermitted extends LogicException implements AuthorizationException
{
    /**
     * IdentityNotAuthorized constructor.
     */
    public function __construct()
    {
        parent::__construct('Identity is not permitted by permission.');
    }
}

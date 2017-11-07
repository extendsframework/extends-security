<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Exception;

use ExtendsFramework\Security\Authorization\AuthorizationException;
use LogicException;

class IdentityNotAssignedToRole extends LogicException implements AuthorizationException
{
    /**
     * IdentityRoleNotAssigned constructor.
     */
    public function __construct()
    {
        parent::__construct('Identity is not assigned to role.');
    }
}

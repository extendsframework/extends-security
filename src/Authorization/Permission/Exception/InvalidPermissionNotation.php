<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Permission\Exception;

use ExtendsFramework\Security\Authorization\Permission\PermissionException;
use InvalidArgumentException;

class InvalidPermissionNotation extends InvalidArgumentException implements PermissionException
{
    /**
     * InvalidPermission constructor.
     *
     * @param string $permission
     */
    public function __construct(string $permission)
    {
        parent::__construct(sprintf(
            'Invalid permission notation detected, got "%s".',
            $permission
        ));
    }
}

<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authentication\Exception;

use ExtendsFramework\Security\Authentication\AuthenticationException;
use LogicException;

class AuthenticationFailed extends LogicException implements AuthenticationException
{
    /**
     * AuthenticationNotSupported constructor.
     */
    public function __construct()
    {
        parent::__construct('No realm has succesfully authenticated token.');
    }
}

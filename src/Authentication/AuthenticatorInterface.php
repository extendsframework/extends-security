<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authentication;

use ExtendsFramework\Security\Authentication\Token\TokenInterface;

interface AuthenticatorInterface
{
    /**
     * Authenticate $token.
     *
     * An exception will be thrown when authentication fails or $token is not supported.
     *
     * @param TokenInterface $token
     * @return AuthenticationInfoInterface
     * @throws AuthenticationException
     */
    public function authenticate(TokenInterface $token): AuthenticationInfoInterface;
}

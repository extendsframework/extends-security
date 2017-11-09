<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authentication\Realm;

use ExtendsFramework\Security\Authentication\AuthenticationException;
use ExtendsFramework\Security\Authentication\AuthenticationInfoInterface;
use ExtendsFramework\Security\Authentication\Token\TokenInterface;

interface RealmInterface
{
    /**
     * If this realm can authenticate $token.
     *
     * @param TokenInterface $token
     * @return bool
     */
    public function canAuthenticate(TokenInterface $token): bool;

    /**
     * Get authentication information for $token.
     *
     * When authentication fails, an exception will be thrown.
     *
     * @param TokenInterface $token
     * @return AuthenticationInfoInterface|null
     * @throws AuthenticationException
     */
    public function getAuthenticationInfo(TokenInterface $token): ?AuthenticationInfoInterface;
}

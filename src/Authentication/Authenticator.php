<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authentication;

use ExtendsFramework\Security\Authentication\Exception\AuthenticationFailed;
use ExtendsFramework\Security\Authentication\Realm\RealmInterface;
use ExtendsFramework\Security\Authentication\Token\TokenInterface;

class Authenticator implements AuthenticatorInterface
{
    /**
     * Realms to use for authentication.
     *
     * @var RealmInterface[]
     */
    protected $realms = [];

    /**
     * @inheritDoc
     */
    public function authenticate(TokenInterface $token): AuthenticationInfoInterface
    {
        foreach ($this->realms as $realm) {
            if ($realm->canAuthenticate($token) === true) {
                $info = $realm->getAuthenticationInfo($token);
                if ($info instanceof AuthenticationInfoInterface) {
                    return $info;
                }
            }
        }

        throw new AuthenticationFailed();
    }

    /**
     * Add $realm to authenticator.
     *
     * @param RealmInterface $realm
     * @return Authenticator
     */
    public function addRealm(RealmInterface $realm): Authenticator
    {
        $this->realms[] = $realm;

        return $this;
    }
}

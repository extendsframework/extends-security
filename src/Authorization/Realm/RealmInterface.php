<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Realm;

use ExtendsFramework\Security\Authorization\AuthorizationInfoInterface;
use ExtendsFramework\Security\Identity\IdentityInterface;

interface RealmInterface
{
    /**
     * Get authorization information for $identity.
     *
     * @param IdentityInterface $identity
     * @return AuthorizationInfoInterface|null
     */
    public function getAuthorizationInfo(IdentityInterface $identity): ?AuthorizationInfoInterface;
}

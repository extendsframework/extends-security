<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Authentication\AuthenticationInfoInterface;
use ExtendsFramework\Authentication\AuthenticatorInterface;
use ExtendsFramework\Authentication\Header\HeaderInterface;
use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Authorization\Permission\Permission;
use ExtendsFramework\Authorization\Policy\PolicyInterface;
use ExtendsFramework\Authorization\Role\Role;
use ExtendsFramework\Identity\IdentityInterface;
use ExtendsFramework\Identity\Storage\StorageInterface;

class SecurityService implements SecurityServiceInterface
{
    /**
     * Authenticator
     *
     * @var AuthenticatorInterface
     */
    private $authenticator;

    /**
     * Authorizer.
     *
     * @var AuthorizerInterface
     */
    private $authorizer;

    /**
     * Identity storage.
     *
     * @var StorageInterface
     */
    private $storage;

    /**
     * SecurityService constructor.
     *
     * @param AuthenticatorInterface $authenticator
     * @param AuthorizerInterface $authorizer
     * @param StorageInterface $storage
     */
    public function __construct(
        AuthenticatorInterface $authenticator,
        AuthorizerInterface $authorizer,
        StorageInterface $storage
    ) {
        $this->authenticator = $authenticator;
        $this->authorizer = $authorizer;
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(HeaderInterface $header): bool
    {
        $info = $this->authenticator->authenticate($header);
        if ($info instanceof AuthenticationInfoInterface) {
            $this->storage->setIdentity($info->getIdentity());

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isPermitted(string $permission): bool
    {
        $identity = $this->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $this->authorizer->isPermitted($identity, new Permission($permission));
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): ?IdentityInterface
    {
        $identity = $this->storage->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $identity;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasRole(string $role): bool
    {
        $identity = $this->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $this->authorizer->hasRole($identity, new Role($role));
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isAllowed(PolicyInterface $policy): bool
    {
        $identity = $this->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $this->authorizer->isAllowed($identity, $policy);
        }

        return false;
    }
}

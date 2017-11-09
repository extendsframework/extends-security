<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Security\Authentication\AuthenticatorInterface;
use ExtendsFramework\Security\Authentication\Token\TokenInterface;
use ExtendsFramework\Security\Authorization\AuthorizerInterface;
use ExtendsFramework\Security\Authorization\Permission\Permission;
use ExtendsFramework\Security\Authorization\Role\Role;
use ExtendsFramework\Security\Exception\IdentityNotFound;
use ExtendsFramework\Security\Identity\Identity;
use ExtendsFramework\Security\Identity\IdentityInterface;
use ExtendsFramework\Security\Identity\Storage\StorageInterface;

class SecurityService implements SecurityServiceInterface
{
    /**
     * Authenticator
     *
     * @var AuthenticatorInterface
     */
    protected $authenticator;

    /**
     * Authorizer.
     *
     * @var AuthorizerInterface
     */
    protected $authorizer;

    /**
     * Identity storage.
     *
     * @var StorageInterface
     */
    protected $storage;

    /**
     * SecurityService constructor.
     *
     * @param AuthenticatorInterface $authenticator
     * @param AuthorizerInterface    $authorizer
     * @param StorageInterface       $storage
     */
    public function __construct(AuthenticatorInterface $authenticator, AuthorizerInterface $authorizer, StorageInterface $storage)
    {
        $this->authenticator = $authenticator;
        $this->authorizer = $authorizer;
        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function authenticate(TokenInterface $token): SecurityServiceInterface
    {
        $info = $this->authenticator->authenticate($token);
        $this->storage->setIdentity(
            new Identity($info->getIdentifier())
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): IdentityInterface
    {
        $identity = $this->storage->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $identity;
        }

        throw new IdentityNotFound();
    }

    /**
     * @inheritDoc
     */
    public function isPermitted(string $permission): bool
    {
        return $this->authorizer->isPermitted(
            $this->getIdentity(),
            new Permission($permission)
        );
    }

    /**
     * @inheritDoc
     */
    public function checkPermission(string $permission): SecurityServiceInterface
    {
        $this->authorizer->checkPermission(
            $this->getIdentity(),
            new Permission($permission)
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasRole(string $role): bool
    {
        return $this->authorizer->hasRole(
            $this->getIdentity(),
            new Role($role)
        );
    }

    /**
     * @inheritDoc
     */
    public function checkRole(string $role): SecurityServiceInterface
    {
        $this->authorizer->checkRole(
            $this->getIdentity(),
            new Role($role)
        );

        return $this;
    }
}

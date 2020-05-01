<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Authentication\AuthenticatorInterface;
use ExtendsFramework\Authentication\Header\HeaderInterface;
use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Authorization\Permission\Permission;
use ExtendsFramework\Authorization\Role\Role;
use ExtendsFramework\Identity\Identity;
use ExtendsFramework\Identity\IdentityInterface;
use ExtendsFramework\Identity\Storage\StorageInterface;
use ExtendsFramework\Security\Exception\IdentityNotFound;

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
     * @param AuthorizerInterface    $authorizer
     * @param StorageInterface       $storage
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
    public function authenticate(HeaderInterface $header): SecurityServiceInterface
    {
        $info = $this->authenticator->authenticate($header);
        $this->storage->setIdentity(new Identity($info->getIdentifier()));

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
        return $this->authorizer->isPermitted($this->getIdentity(), new Permission($permission));
    }

    /**
     * @inheritDoc
     */
    public function checkPermission(string $permission): SecurityServiceInterface
    {
        $this->authorizer->checkPermission($this->getIdentity(), new Permission($permission));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasRole(string $role): bool
    {
        return $this->authorizer->hasRole($this->getIdentity(), new Role($role));
    }

    /**
     * @inheritDoc
     */
    public function checkRole(string $role): SecurityServiceInterface
    {
        $this->authorizer->checkRole($this->getIdentity(), new Role($role));

        return $this;
    }
}

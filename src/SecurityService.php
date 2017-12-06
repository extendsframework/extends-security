<?php
declare(strict_types=1);

namespace ExtendsFramework\Security;

use ExtendsFramework\Authentication\AuthenticatorInterface;
use ExtendsFramework\Authentication\Token\TokenInterface;
use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Authorization\Permission\Permission;
use ExtendsFramework\Authorization\Role\Role;
use ExtendsFramework\Security\Exception\IdentityNotFound;
use ExtendsFramework\Identity\Identity;
use ExtendsFramework\Identity\IdentityInterface;
use ExtendsFramework\Identity\Storage\StorageInterface;

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
        $info = $this
            ->getAuthenticator()
            ->authenticate($token);
        $this
            ->getStorage()
            ->setIdentity(
                new Identity($info->getIdentifier())
            );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): IdentityInterface
    {
        $identity = $this
            ->getStorage()
            ->getIdentity();
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
        return $this
            ->getAuthorizer()
            ->isPermitted(
                $this->getIdentity(),
                new Permission($permission)
            );
    }

    /**
     * @inheritDoc
     */
    public function checkPermission(string $permission): SecurityServiceInterface
    {
        $this
            ->getAuthorizer()
            ->checkPermission(
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
        return $this
            ->getAuthorizer()
            ->hasRole(
                $this->getIdentity(),
                new Role($role)
            );
    }

    /**
     * @inheritDoc
     */
    public function checkRole(string $role): SecurityServiceInterface
    {
        $this
            ->getAuthorizer()
            ->checkRole(
                $this->getIdentity(),
                new Role($role)
            );

        return $this;
    }

    /**
     * Get authenticator.
     *
     * @return AuthenticatorInterface
     */
    protected function getAuthenticator(): AuthenticatorInterface
    {
        return $this->authenticator;
    }

    /**
     * Get authorizer.
     *
     * @return AuthorizerInterface
     */
    protected function getAuthorizer(): AuthorizerInterface
    {
        return $this->authorizer;
    }

    /**
     * Get storage.
     *
     * @return StorageInterface
     */
    protected function getStorage(): StorageInterface
    {
        return $this->storage;
    }
}

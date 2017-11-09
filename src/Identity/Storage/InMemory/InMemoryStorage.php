<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Identity\Storage\InMemory;

use ExtendsFramework\Security\Identity\IdentityInterface;
use ExtendsFramework\Security\Identity\Storage\StorageInterface;

class InMemoryStorage implements StorageInterface
{
    /**
     * Temporary stored identity.
     *
     * @var IdentityInterface
     */
    protected $identity;

    /**
     * @inheritDoc
     */
    public function getIdentity(): ?IdentityInterface
    {
        return $this->identity;
    }

    /**
     * @inheritDoc
     */
    public function setIdentity(IdentityInterface $identity): StorageInterface
    {
        $this->identity = $identity;

        return $this;
    }
}

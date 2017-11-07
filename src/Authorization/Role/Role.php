<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Role;

class Role implements RoleInterface
{
    /**
     * Role name.
     *
     * @var string
     */
    protected $name;

    /**
     * Role constructor.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function isEqual(RoleInterface $role): bool
    {
        return $this->getName() === $role->getName();
    }
}

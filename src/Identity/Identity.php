<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Identity;

class Identity implements IdentityInterface
{
    /**
     * Identity identifier.
     *
     * @var string
     */
    protected $identifier;

    /**
     * Identity constructor.
     *
     * @param string $identifier
     */
    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}

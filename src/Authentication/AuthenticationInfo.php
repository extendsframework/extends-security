<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authentication;

class AuthenticationInfo implements AuthenticationInfoInterface
{
    /**
     * Identifier.
     *
     * @var string
     */
    protected $identifier;

    /**
     * AuthenticationInfo constructor.
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

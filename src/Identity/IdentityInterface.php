<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Identity;

interface IdentityInterface
{
    /**
     * Get identity identifier.
     *
     * @return string
     */
    public function getIdentifier(): string;
}

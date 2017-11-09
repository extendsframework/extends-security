<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authentication;

use PHPUnit\Framework\TestCase;

class AuthenticationInfoTest extends TestCase
{
    /**
     * Get identifier.
     *
     * Test that correct identifier will be returned.
     *
     * @covers \ExtendsFramework\Security\Authentication\AuthenticationInfo::__construct()
     * @covers \ExtendsFramework\Security\Authentication\AuthenticationInfo::getIdentifier()
     */
    public function testGetIdentifier(): void
    {
        $info = new AuthenticationInfo('foo-bar-baz');

        $this->assertSame('foo-bar-baz', $info->getIdentifier());
    }
}

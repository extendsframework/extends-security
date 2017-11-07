<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Role;

use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    /**
     * Get name.
     *
     * Test that correct name is returned.
     *
     * @covers \ExtendsFramework\Security\Authorization\Role\Role::__construct()
     * @covers \ExtendsFramework\Security\Authorization\Role\Role::getName()
     */
    public function testGetName(): void
    {
        $role = new Role('administrator');

        $this->assertSame('administrator', $role->getName());
    }

    /**
     * Is equal.
     *
     * Test that both roles are considered equal.
     *
     * @covers \ExtendsFramework\Security\Authorization\Role\Role::__construct()
     * @covers \ExtendsFramework\Security\Authorization\Role\Role::getName()
     * @covers \ExtendsFramework\Security\Authorization\Role\Role::isEqual()
     */
    public function testIsEqual(): void
    {
        $role = new Role('administrator');

        $this->assertTrue($role->isEqual(new Role('administrator')));
    }
}

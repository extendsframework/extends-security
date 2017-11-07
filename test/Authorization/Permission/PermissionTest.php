<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Permission;

use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    /**
     * Implies.
     *
     * Test that implies method will return correct boolean.
     *
     * @covers \ExtendsFramework\Security\Authorization\Permission\Permission::__construct()
     * @covers \ExtendsFramework\Security\Authorization\Permission\Permission::implies()
     * @covers \ExtendsFramework\Security\Authorization\Permission\Permission::getSections()
     */
    public function testImpliesExactMatches(): void
    {
        // Exact matches.
        $this->assertTrue((new Permission('foo:bar:baz'))->implies(new Permission('foo:bar:baz')));
        $this->assertTrue((new Permission('foo:bar'))->implies(new Permission('foo:bar')));
        $this->assertTrue((new Permission('foo:*'))->implies(new Permission('foo')));
        $this->assertTrue((new Permission('foo'))->implies(new Permission('foo')));

        // Stronger matches.
        $this->assertTrue((new Permission('foo:bar:*'))->implies(new Permission('foo:bar:baz')));
        $this->assertTrue((new Permission('foo:bar'))->implies(new Permission('foo:bar:baz')));
        $this->assertTrue((new Permission('foo:*'))->implies(new Permission('foo:bar:baz')));
        $this->assertTrue((new Permission('foo'))->implies(new Permission('foo:bar:baz')));

        // Weaker matches.
        $this->assertFalse((new Permission('foo:bar:baz'))->implies(new Permission('foo:bar:*')));
        $this->assertFalse((new Permission('foo:bar:baz'))->implies(new Permission('foo:bar')));
        $this->assertFalse((new Permission('foo:baz'))->implies(new Permission('foo:bar')));
    }

    /**
     * Invalid permission string.
     *
     * Test that an invalid permission notation is not allowed.
     *
     * @covers                   \ExtendsFramework\Security\Authorization\Permission\Permission::__construct()
     * @covers                   \ExtendsFramework\Security\Authorization\Permission\Exception\InvalidPermissionNotation::__construct()
     * @expectedException        \ExtendsFramework\Security\Authorization\Permission\Exception\InvalidPermissionNotation
     * @expectedExceptionMessage Invalid permission notation detected, got "foo,:bar".
     */
    public function testInvalidPermission(): void
    {
        new Permission('foo,:bar');
    }

    /**
     * Not same instance.
     *
     * Test that permission can not imply other instance of PermissionInterface.
     *
     * @covers \ExtendsFramework\Security\Authorization\Permission\Permission::__construct()
     * @covers \ExtendsFramework\Security\Authorization\Permission\Permission::implies()
     */
    public function testNotSameInstance(): void
    {
        $this->assertFalse((new Permission('*'))->implies(new PermissionStub()));
    }
}

class PermissionStub implements PermissionInterface
{
    /**
     * @inheritDoc
     */
    public function implies(PermissionInterface $permission): bool
    {
        return true;
    }
}

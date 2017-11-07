<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Realm\Pdo;

use ExtendsFramework\Security\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Security\Authorization\Role\RoleInterface;
use ExtendsFramework\Security\Identity\IdentityInterface;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class PdoRealmTest extends TestCase
{
    /**
     * Get authorization info.
     *
     * Test that permissions and roles will be loaded from PDO instance.
     *
     * @covers \ExtendsFramework\Security\Authorization\Realm\Pdo\PdoRealm::__construct()
     * @covers \ExtendsFramework\Security\Authorization\Realm\Pdo\PdoRealm::getAuthorizationInfo()
     * @covers \ExtendsFramework\Security\Authorization\Realm\Pdo\PdoRealm::addPermissions()
     * @covers \ExtendsFramework\Security\Authorization\Realm\Pdo\PdoRealm::addRoles()
     */
    public function testGetAuthorizationInfo()
    {
        $identity = $this->createMock(IdentityInterface::class);
        $identity
            ->expects($this->exactly(3))
            ->method('getIdentifier')
            ->willReturn('043ca5cc-95db-43e2-aa0e-c8575f706d33');

        $statement = $this->createMock(PDOStatement::class);
        $statement
            ->expects($this->exactly(2))
            ->method('execute')
            ->withConsecutive(
                [
                    [
                        '043ca5cc-95db-43e2-aa0e-c8575f706d33',
                        '043ca5cc-95db-43e2-aa0e-c8575f706d33'
                    ]
                ],
                [
                    [
                        '043ca5cc-95db-43e2-aa0e-c8575f706d33'
                    ]
                ]
            );

        $statement
            ->expects($this->exactly(2))
            ->method('fetchAll')
            ->willReturnOnConsecutiveCalls(
                [
                    [
                        'permission_id' => '1',
                        'notation' => 'foo:bar:*',
                    ],
                    [
                        'permission_id' => '2',
                        'notation' => 'baz:*',
                    ],
                ],
                [
                    [
                        'role_id' => '1',
                        'name' => 'administrator',
                    ],
                    [
                        'role_id' => '2',
                        'name' => 'moderator',
                    ]
                ]
            );

        $pdo = $this->createMock(PDO::class);
        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn($statement);

        /**
         * @var PDO               $pdo
         * @var IdentityInterface $identity
         */
        $realm = new PdoRealm($pdo);
        $info = $realm->getAuthorizationInfo($identity);

        $this->assertContainsOnlyInstancesOf(PermissionInterface::class, $info->getPermissions());
        $this->assertContainsOnlyInstancesOf(RoleInterface::class, $info->getRoles());
    }
}

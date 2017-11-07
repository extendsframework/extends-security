<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Realm\Pdo;

use ExtendsFramework\Security\Authorization\AuthorizationInfo;
use ExtendsFramework\Security\Authorization\AuthorizationInfoInterface;
use ExtendsFramework\Security\Authorization\Permission\Permission;
use ExtendsFramework\Security\Authorization\Realm\RealmInterface;
use ExtendsFramework\Security\Authorization\Role\Role;
use ExtendsFramework\Security\Identity\IdentityInterface;
use PDO;

class PdoRealm implements RealmInterface
{
    /**
     * PDO.
     *
     * @var PDO
     */
    protected $pdo;

    /**
     * Identity permissions query.
     *
     * @var string
     */
    protected $permissionsQuery = '
        SELECT p.*
        FROM permission AS p
        INNER JOIN identity_permission AS ip USING (permission_id)
        INNER JOIN identity AS i USING (identity_id)
        WHERE i.identifier = ?
        
        UNION DISTINCT
        
        SELECT p.*
        FROM permission AS p
        INNER JOIN role_permission AS rp USING (permission_id)
        INNER JOIN role AS r USING (role_id)
        INNER JOIN identity_role AS ir USING (role_id)
        INNER JOIN identity AS i USING (identity_id)
        WHERE i.identifier = ?
    ';

    /**
     * Identity roles query.
     *
     * @var string
     */
    protected $rolesQuery = '
        SELECT r.*
        FROM role AS r
        INNER JOIN identity_role AS ir USING (role_id)
        INNER JOIN identity AS i USING (identity_id)
        WHERE i.identifier = ?
    ';

    /**
     * PdoRealm constructor.
     *
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @inheritDoc
     */
    public function getAuthorizationInfo(IdentityInterface $identity): AuthorizationInfoInterface
    {
        $info = new AuthorizationInfo();
        $this
            ->addPermissions($identity, $info)
            ->addRoles($identity, $info);

        return $info;
    }

    /**
     * Add permissions for $identity to $info.
     *
     * @param IdentityInterface $identity
     * @param AuthorizationInfo $info
     * @return PdoRealm
     */
    protected function addPermissions(IdentityInterface $identity, AuthorizationInfo $info): PdoRealm
    {
        $statement = $this->pdo->prepare($this->permissionsQuery);
        $statement->execute([
            $identity->getIdentifier(),
            $identity->getIdentifier(),
        ]);

        foreach ($statement->fetchAll() as $permission) {
            $info->addPermission(
                new Permission($permission['notation'])
            );
        }

        return $this;
    }

    /**
     * Add roles for $identity to $info.
     *
     * @param IdentityInterface $identity
     * @param AuthorizationInfo $info
     * @return PdoRealm
     */
    protected function addRoles(IdentityInterface $identity, AuthorizationInfo $info): PdoRealm
    {
        $statement = $this->pdo->prepare($this->rolesQuery);
        $statement->execute([
            $identity->getIdentifier(),
        ]);

        foreach ($statement->fetchAll() as $role) {
            $info->addRole(
                new Role($role['name'])
            );
        }

        return $this;
    }
}

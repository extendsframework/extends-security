<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Identity\Storage\InMemory;

use ExtendsFramework\Security\Identity\IdentityInterface;
use PHPUnit\Framework\TestCase;

class InMemoryStorageTest extends TestCase
{
    /**
     * Identity.
     *
     * Test that set identity will be returned.
     *
     * @covers \ExtendsFramework\Security\Identity\Storage\InMemory\InMemoryStorage::setIdentity()
     * @covers \ExtendsFramework\Security\Identity\Storage\InMemory\InMemoryStorage::getIdentity()
     */
    public function testIdentity(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        /**
         * @var IdentityInterface $identity
         */
        $storage = new InMemoryStorage();
        $stored = $storage
            ->setIdentity($identity)
            ->getIdentity();

        $this->assertSame($stored, $identity);
    }
}

<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Doctrine\Repository;

use App\Domain\Repository\MessageRepositoryInterface;
use App\Infrastructure\Doctrine\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');

        $this->entityManager = $entityManager;

        $this->entityManager->getConnection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->entityManager->getConnection()->rollback();
        parent::tearDown();
    }

    public function test_it_has_connection(): void
    {
        /** @var MessageRepositoryInterface $messageRepository */
        $messageRepository = self::getContainer()->get(MessageRepository::class);

        $this->assertSame([], $messageRepository->findAll());
    }
}

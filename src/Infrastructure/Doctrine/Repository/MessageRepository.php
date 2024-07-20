<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Message;
use App\Domain\Repository\MessageRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Poposki\KernelBundle\Infrastructure\Doctrine\Repository\AbstractRepository;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class MessageRepository extends AbstractRepository implements MessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * - Wraps the findBy method for a status lookup
     *
     * {@inheritDoc}
     */
    public function findByStatus(string $status): array
    {
        return $this->findBy(['status' => $status]);
    }

    /**
     * - Stores the message entity
     *
     * {@inheritDoc}
     */
    public function add(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

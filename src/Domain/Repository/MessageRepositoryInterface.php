<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Message;

interface MessageRepositoryInterface
{
    /**
     * @param string $status
     * @return Message[]
     */
    public function findByStatus(string $status): array;

    /**
     * @return Message[]
     */
    public function findAll(): array;

    /**
     * @param Message $entity
     * @param bool $flush
     * @return void
     */
    public function add(Message $entity, bool $flush = false): void;
}

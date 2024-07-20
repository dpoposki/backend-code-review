<?php
declare(strict_types=1);

namespace App\Application\Messenger\Query\Handler;

use App\Application\Messenger\Query\MessageListQuery;
use App\Domain\Entity\Message;
use App\Domain\Repository\MessageRepositoryInterface;
use Poposki\KernelBundle\Application\Messenger\Query\QueryHandlerInterface;

final readonly class MessageListQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository
    ) {
    }

    /**
     * - Considering that we have a MessageRepository, I decided to work
     *  through it as the best practice instead of calling the entity manager.
     *  - I created a findByStatus wrapper around findBy for design purposes.
     *
     * @param MessageListQuery $query
     * @return Message[]
     */
    public function __invoke(MessageListQuery $query): array
    {
        if (null === $query->getStatus()) {
            return $this->messageRepository->findAll();
        }

        return $this->messageRepository
            ->findByStatus($query->getStatus());
    }
}

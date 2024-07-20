<?php
declare(strict_types=1);

namespace App\Application\Messenger\Command\Handler;

use App\Application\Messenger\Command\MessageAddCommand;
use App\Domain\Entity\Message;
use App\Domain\Repository\MessageRepositoryInterface;
use Poposki\KernelBundle\Application\Messenger\Command\CommandHandlerInterface;
use Symfony\Component\Uid\Uuid;

final readonly class MessageAddCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository
    ) {
    }

    /**
     * - We can definitely outsource this part to a MessageFactory class
     *  that will create the Message object, but I feel it's an overkill
     *  at this point, so I create it here.
     *  - I have modified the Message class in a way to accept params only
     *  through the constructor after which I store it via the MessageRepository.
     *  - The handler is wrapped in a database transaction which flushes the
     *  call if everything went successfully.
     *
     * @param MessageAddCommand $command
     * @return void
     */
    public function __invoke(MessageAddCommand $command): void
    {
        $message = new Message(
            Uuid::v6()->toRfc4122(),
            $command->getText(),
            'sent'
        );

        $this->messageRepository->add($message);
    }
}

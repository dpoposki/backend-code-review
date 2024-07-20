<?php
declare(strict_types=1);

namespace App\Tests\Application\Messenger\Command\Handler;

use App\Application\Messenger\Command\Handler\MessageAddCommandHandler;
use App\Application\Messenger\Command\MessageAddCommand;
use App\Domain\Entity\Message;
use App\Domain\Repository\MessageRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class MessageAddCommandHandlerTest extends TestCase
{
    public function test_it_handles_message_add_command(): void
    {
        $messageRepository = $this->createMock(MessageRepositoryInterface::class);

        $messageRepository->expects($this->once())
            ->method('add')
            ->with($this->callback(function (Message $message) {
                return $message->getText() === 'Hello World' &&
                    $message->getStatus() === 'sent' &&
                    Uuid::isValid($message->getUuid());
            }));

        $handler = new MessageAddCommandHandler($messageRepository);
        $command = new MessageAddCommand('Hello World');

        $handler->__invoke($command);
    }
}

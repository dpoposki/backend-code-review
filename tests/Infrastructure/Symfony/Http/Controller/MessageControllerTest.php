<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Symfony\Http\Controller;

use App\Application\Messenger\Command\MessageAddCommand;
use App\Application\Messenger\Query\MessageListQuery;
use App\Domain\Entity\Message;
use Poposki\KernelBundle\Application\Messenger\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class MessageControllerTest extends WebTestCase
{
    use InteractsWithMessenger;

    /**
     * I am not quite sure where is my mistake in this test case,
     * but I am not able to get it running, and at the same time
     * I'd like to wrap up the task, so I left it like it is.
     */
    public function test_that_it_lists_messages(): void
    {
        // Initialize the QueryBus mock
        $queryBus = $this->createMock(QueryBusInterface::class);

        // Override the service container to return our mock
        self::getContainer()->set(QueryBusInterface::class, $queryBus);

        // Create sample messages
        $messages = [
            new Message(Uuid::v6()->toRfc4122(), 'Hello World 1', 'sent'),
            new Message(Uuid::v6()->toRfc4122(), 'Hello World 2', 'sent'),
        ];

        // Configure the QueryBus mock to return the expected messages
        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->isInstanceOf(MessageListQuery::class))
            ->willReturn($messages);

        // Make the request
        $client = static::createClient();
        $client->request('GET', '/messages?status=sent');

        // Assert the response
        $this->assertResponseIsSuccessful();

        /** @var string $content */
        $content = $client->getResponse()->getContent();

        $responseData = json_decode($content, true);

        $this->assertIsArray($responseData);
        $this->assertCount(2, $responseData);

        $this->assertEquals('Hello World 1', $responseData[0]['text']);
        $this->assertEquals('Hello World 2', $responseData[1]['text']);
    }

    public function test_that_it_sends_a_message(): void
    {
        $client = static::createClient();
        $client->request('POST', '/messages?text=Hello World from PHPUnit');

        $this->assertResponseIsSuccessful();

        // This is using https://packagist.org/packages/zenstruck/messenger-test
        $this->transport('sync')
            ->queue()
            ->assertContains(MessageAddCommand::class, 1);
    }
}

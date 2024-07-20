<?php
declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Controller;

use App\Application\Messenger\Command\MessageAddCommand;
use App\Application\Messenger\Query\MessageListQuery;
use App\Domain\Entity\Message;
use App\Infrastructure\Symfony\Http\Response\MessageListResponse;
use App\Infrastructure\Symfony\Http\Response\ValidationFailedResponse;
use Poposki\KernelBundle\Infrastructure\Symfony\Http\Web\Controller\AbstractController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    /**
     * - I have quite recently created an example on Hexagonal architecture and CQRS pattern
     * which you can find at https://github.com/dpoposki/hexagonal-cqrs. I have extracted the
     * Kernel module in this example as a Symfony bundle in order to achieve this task in a
     * more advanced fashion. The Symfony bundle can be found at https://github.com/dpoposki/symfony-kernel.
     * - Considering that the project sample uses the Messenger component and makes use of the
     * Messenger Bus I have decided to implement the Hexagonal architecture and CQRS, meaning
     * I have used a Command Message bus for the POST request, and a Query Message bus for the
     * GET request. This will simplify and remove any business logic from the controllers, as
     * we would like to move the business logic inside the Service layer, or in this case the
     * Application and Domain layers.
     * - I can get the status as a method parameter, but I prefer getting the Request, and
     * eventually building a data transfer object. This might be in collision with the Law
     * of Demeter, but it's a business choice I make based on the possibility of building
     * DTOs. Afterward, I send only the status to the lower layers in order to respect the
     * Law of Demeter.
     * - We return with a response object that knows how to build the output.
     */
    #[Route('/messages', methods: ['GET'])]
    public function list(Request $request): Response
    {
        try {
            /** @var string|null $status */
            $status = $request->query->get('status');

            /** @var Message[] $messages */
            $messages = $this->ask(new MessageListQuery($status));
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $exception) {
            return new JsonResponse('Something went super wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new MessageListResponse($messages);
    }

    /**
     * - Considering that this is a 'create' action (it adds a message), this should
     * be a POST request to /messages instead of a GET request to /messages/send in
     * order to follow the REST standard.
     * - Same as the previous controller method I can get the text as a method parameter,
     * but I prefer getting the Request, and eventually building a data transfer object.
     * - We handle the command via the command message bus, validate the command and
     * wrap it in a database transaction.
     * - Possible outcomes are a successful addition of the message, a validation failure,
     * or an issue with the dependency injection.
     */
    #[Route('/messages', methods: ['POST'])]
    public function send(Request $request): Response
    {
        try {
            /** @var string $text */
            $text = $request->query->get('text', '');

            $this->handle(new MessageAddCommand($text));
        } catch (ValidationFailedException $exception) {
            return new ValidationFailedResponse($exception->getViolations());
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $exception) {
            return new JsonResponse('Something went super wrong.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse('Successfully added the message.', Response::HTTP_CREATED);
    }
}

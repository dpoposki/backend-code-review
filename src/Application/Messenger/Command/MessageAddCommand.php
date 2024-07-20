<?php
declare(strict_types=1);

namespace App\Application\Messenger\Command;

use Poposki\KernelBundle\Application\Messenger\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class MessageAddCommand implements CommandInterface
{
    /**
     * - Although with PHP8 we can use a public readonly property
     * and avoid using getters/setters, I prefer using private
     * properties set in the constructor for immutability and then
     * working with getters. It's just a design choice I make, but
     * I understand that both are good enough nowadays.
     * - We validate the $text variable at this point as a middleware
     * triggered before the handler.
     */
    public function __construct(
        #[Assert\NotBlank(message: 'The text field is required')]
        private string $text
    ) {
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}

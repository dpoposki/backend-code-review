<?php
declare(strict_types=1);

namespace App\Application\Messenger\Query;

use Poposki\KernelBundle\Application\Messenger\Query\QueryInterface;

final readonly class MessageListQuery implements QueryInterface
{
    public function __construct(
        private ?string $status
    ) {}

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}

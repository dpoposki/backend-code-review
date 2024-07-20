<?php

namespace App\Domain\Entity;

use Poposki\KernelBundle\Domain\Entity\EntityInterface;

/**
 * - I have removed all setters from the class in order to avoid mutability.
 * - I have implemented a Doctrine listener for the prePersist event in
 * order to set the createdAt datetime automatically.
 * - I have removed the entire Doctrine configuration from the class in order
 * to keep it decoupled from the framework and have placed it in the Domain layer.
 */
class Message implements EntityInterface
{
    private ?int $id = null;

    private string $uuid;

    private string $text;

    private ?string $status;

    private \DateTimeInterface $createdAt;

    public function __construct(
        string $uuid,
        string $text,
        ?string $status = null
    ) {
        $this->uuid = $uuid;
        $this->text = $text;
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return void
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}

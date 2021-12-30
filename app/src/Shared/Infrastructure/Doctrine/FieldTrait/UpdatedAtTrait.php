<?php

namespace App\Shared\Infrastructure\Doctrine\FieldTrait;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtTrait
{
    /**
     * @ORM\Column(name="updated_at", type="datetime")
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private DateTimeImmutable $updatedAt;


    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }
}

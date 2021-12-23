<?php

namespace App\Shared\Infrastructure\Doctrine;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtTrait
{
    /**
     * @ORM\Column(name="updated_at", type="datetime")
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private DateTime $updatedAt;


    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTime();

        return $this;
    }
}

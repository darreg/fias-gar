<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait StatusTrait
{
    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private ?bool $status;

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }
}

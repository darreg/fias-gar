<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
final class Name
{
    /**
     * @ORM\Column(type="string")
     */
    private string $first;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $last;

    public function __construct(string $first, ?string $last)
    {
        Assert::notEmpty($first);
        $this->first = $first;
        $this->last = $last;
    }

    public function getFirst(): string
    {
        return $this->first;
    }

    public function getLast(): ?string
    {
        return $this->last;
    }

    public function getFull(): string
    {
        return trim($this->first . ' ' . ($this->last ?? ''));
    }
}

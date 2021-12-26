<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\Entity\ExtHouse;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

class House
{
    /**
     * @ORM\Column(type="bigint")
     */
    private int $objectid;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private string $objectguid;

    /**
     * @param int $objectid
     * @param string $objectguid
     */
    public function __construct(int $objectid, string $objectguid)
    {
        Assert::notEmpty($objectid);
        Assert::notEmpty($objectguid);

        $this->objectid = $objectid;
        $this->objectguid = $objectguid;
    }

    public function getObjectid(): int
    {
        return $this->objectid;
    }

    public function getObjectguid(): string
    {
        return $this->objectguid;
    }
}
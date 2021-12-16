<?php

namespace App\BackOffice\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * @MappedSuperclass
 *
 * @psalm-suppress MissingConstructor
 */
class Addrobj
{
    /**
     * @ORM\Column(type="bigint")
     * @ORM\Id()
     */
    private ?int $objectid;

    /**
     * @ORM\OneToOne(targetEntity=ExtAddrobj::class, fetch="EAGER")
     * @JoinColumn(name="objectid", referencedColumnName="objectid")
     */
    private ?ExtAddrobj $extAddrobj;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private ?string $objectguid;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $type;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private ?string $fulltype;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $level;

    /**
     * @ORM\Column(type="date")
     */
    private DateTime $updatedate;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private ?int $parentobjid;

    /**
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private ?string $parentobjguid;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private ?string $parentname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $parenttype;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private ?string $parentfulltype;


    public function getObjectid(): ?int
    {
        return $this->objectid;
    }

    public function setObjectid(?int $objectid): self
    {
        $this->objectid = $objectid;

        return $this;
    }

    public function getExtAddrobj(): ?ExtAddrobj
    {
        return $this->extAddrobj;
    }

    public function setExtAddrobj(?ExtAddrobj $extAddrobj): self
    {
        $this->extAddrobj = $extAddrobj;

        return $this;
    }

    public function getObjectguid(): ?string
    {
        return $this->objectguid;
    }

    public function setObjectguid(?string $objectguid): self
    {
        $this->objectguid = $objectguid;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFulltype(): ?string
    {
        return $this->fulltype;
    }

    public function setFulltype(?string $fulltype): self
    {
        $this->fulltype = $fulltype;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getUpdatedate(): DateTime
    {
        return $this->updatedate;
    }

    public function setUpdatedate(DateTime $updatedate): self
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    public function getParentobjid(): ?int
    {
        return $this->parentobjid;
    }

    public function setParentobjid(?int $parentobjid): self
    {
        $this->parentobjid = $parentobjid;

        return $this;
    }

    public function getParentobjguid(): ?string
    {
        return $this->parentobjguid;
    }

    public function setParentobjguid(?string $parentobjguid): self
    {
        $this->parentobjguid = $parentobjguid;

        return $this;
    }

    public function getParentname(): ?string
    {
        return $this->parentname;
    }

    public function setParentname(?string $parentname): self
    {
        $this->parentname = $parentname;

        return $this;
    }

    public function getParenttype(): ?string
    {
        return $this->parenttype;
    }

    public function setParenttype(?string $parenttype): self
    {
        $this->parenttype = $parenttype;

        return $this;
    }

    public function getParentfulltype(): ?string
    {
        return $this->parentfulltype;
    }

    public function setParentfulltype(?string $parentfulltype): self
    {
        $this->parentfulltype = $parentfulltype;

        return $this;
    }
}

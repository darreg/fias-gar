<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExtAddrobjSynonym
 *
 * @ORM\Table(name="ext_addrobj_synonym")
 * @ORM\Entity(repositoryClass="App\Repository\ExtAddrobjSynonymRepository")
 */
class ExtAddrobjSynonym
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=ExtAddrobj::class, inversedBy="synonym")
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid", nullable=true)
     */
    private ?ExtAddrobj $extAddrobj;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
}

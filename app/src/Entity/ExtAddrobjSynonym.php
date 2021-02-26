<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExtAddrobjSynonym
 *
 * @ORM\Table(
 *     name="ext_addrobj_synonym",
 *     indexes={
 *         @ORM\Index(name="ext_addrobj_synonym__id__ind", columns={"id"}),
 *         @ORM\Index(name="ext_addrobj_synonym__objectid__ind", columns={"objectid"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ExtAddrobjSynonymRepository")
 * @ORM\HasLifecycleCallbacks()
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

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private DateTime $updatedAt;

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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAt(): self
    {
        $this->createdAt = new DateTime();

        return $this;
    }

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

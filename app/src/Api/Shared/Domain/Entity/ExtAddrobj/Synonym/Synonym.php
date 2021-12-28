<?php

namespace App\Api\Shared\Domain\Entity\ExtAddrobj\Synonym;

use App\Api\Shared\Domain\Entity\ExtAddrobj\ExtAddrobj;
use App\Shared\Infrastructure\Doctrine\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="ext_addrobj_synonym",
 *     indexes={
 *         @ORM\Index(name="ext_addrobj_synonym__id__ind", columns={"id"}),
 *         @ORM\Index(name="ext_addrobj_synonym__objectid__ind", columns={"objectid"})
 *     },
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="ext_addrobj_synonym__unique__constraint", columns={"objectid", "name"})
 *     }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Synonym
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\ManyToOne(targetEntity=ExtAddrobj::class, inversedBy="synonyms")
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid")
     */
    private ExtAddrobj $extAddrobj;

    /**
     * @ORM\Id()
     * @ORM\Column(type="ext_addrobj_synonym_id")
     */
    private Id $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private string $name;

    public function __construct(ExtAddrobj $extAddrobj, Id $id, string $name)
    {
        $this->extAddrobj = $extAddrobj;
        $this->id = $id;
        $this->name = $name;
    }

    public function isNameEqual(string $name): bool
    {
        return $this->name === $name;
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

    public function getId(): Id
    {
        return $this->id;
    }
}

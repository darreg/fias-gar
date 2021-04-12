<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExtAddrobjSynonym
 *
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
 * @ORM\Entity(repositoryClass="App\Repository\ExtAddrobjSynonymRepository")
 * @ORM\HasLifecycleCallbacks()
 * @psalm-suppress MissingConstructor
 */
class ExtAddrobjSynonym
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity=ExtAddrobj::class, inversedBy="synonyms")
     * @ORM\JoinColumn(name="objectid", referencedColumnName="objectid")
     */
    private ExtAddrobj $extAddrobj;


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

    public function getExtAddrobj(): ExtAddrobj
    {
        return $this->extAddrobj;
    }

    public function setExtAddrobj(ExtAddrobj $extAddrobj): self
    {
        $this->extAddrobj = $extAddrobj;

        return $this;
    }
}

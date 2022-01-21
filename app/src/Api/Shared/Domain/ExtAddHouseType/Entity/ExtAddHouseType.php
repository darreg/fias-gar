<?php

declare(strict_types=1);

namespace App\Api\Shared\Domain\ExtAddHouseType\Entity;

use App\Shared\Infrastructure\Doctrine\FieldTrait\CreatedAtTrait;
use App\Shared\Infrastructure\Doctrine\FieldTrait\UpdatedAtTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="ext_add_house_types",
 *     indexes={
 *         @ORM\Index(name="ext_add_house_types__type_id_ind", columns={"type_id"})
 *     }
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ExtAddHouseType
{
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(type="ext_add_house_type_id")
     */
    private Id $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $typeId;

    public function __construct(Id $id, string $name, int $typeId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->typeId = $typeId;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }
}
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HouseAdm
 *
 * @ORM\Table(name="v_house_adm")
 * @ORM\Entity(readOnly=true, repositoryClass="App\Repository\HouseAdmRepository")
 */
class HouseAdm
{
    use HouseTrait;
}
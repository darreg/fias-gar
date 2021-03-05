<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HouseMun
 *
 * @ORM\Table(name="v_house_mun")
 * @ORM\Entity(readOnly=true, repositoryClass="App\Repository\HouseMunRepository")
 */
class HouseMun extends House
{
}

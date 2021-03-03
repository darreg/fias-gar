<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * AddrobjMun
 *
 * @ORM\Table(name="v_addrobj_mun")
 * @ORM\Entity(readOnly=true, repositoryClass="App\Repository\AddrobjMunRepository")
 */
class AddrobjMun extends Addrobj
{
}
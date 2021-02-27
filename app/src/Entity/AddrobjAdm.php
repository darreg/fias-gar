<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * AddrobjAdm
 *
 * @ORM\Table(name="v_addrobj_adm")
 * @ORM\Entity(readOnly=true, repositoryClass="App\Repository\AddrobjAdmRepository")
 */
class AddrobjAdm
{
    use AddrobjTrait;
}
<?php

namespace App\DTO;

interface ConstructFromArrayInterface
{
    /**
     * @return mixed
     */
    public static function fromArray(array $data);

    /**
     * @return mixed
     */
    public static function fromEntity(object $object);    
}

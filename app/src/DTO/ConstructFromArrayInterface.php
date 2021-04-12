<?php

namespace App\DTO;

interface ConstructFromArrayInterface
{
    /**
     * @return mixed
     */
    public static function fromArray(array $data);
}

<?php

namespace App\BackOffice\DTO;

interface ConstructFromArrayInterface
{
    /**
     * @return mixed
     */
    public static function fromArray(array $data);
}

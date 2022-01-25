<?php

namespace App\Shared\Infrastructure;

interface ConstructFromArrayInterface
{
    /**
     * @return mixed
     */
    public static function fromArray(array $data): self;
}

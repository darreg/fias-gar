<?php

declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use LogicException;
use Throwable;

class ConstructFromArrayException extends LogicException
{
    public function __construct(string $model, int $code, ?Throwable $e)
    {
        parent::__construct(
            sprintf('It is not possible to convert an array of data into a model  %s', $model),
            $code,
            $e
        );
    }
}

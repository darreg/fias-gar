<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\ZipFile\Service;

use App\DataLoad\Domain\ZipFile\Exception\FileRemoveException;

interface ZipFileRotatorInterface
{
    /**
     * @throws FileRemoveException
     */
    public function rotate(): void;
}

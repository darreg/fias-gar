<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\XmlFile\Service;

use App\DataLoad\Domain\XmlFile\Exception\CleanUpException;

interface XmlFileCleanerInterface
{
    /**
     * @throws CleanUpException
     */
    public function clean(): void;
}

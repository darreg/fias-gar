<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

use App\DataLoad\Domain\Entity\File;

interface FileFinderInterface
{
    /**
     * @return list<File>
     */
    public function find(string $token): array;
}

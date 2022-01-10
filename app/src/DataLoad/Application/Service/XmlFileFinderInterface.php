<?php

declare(strict_types=1);

namespace App\DataLoad\Application\Service;

use App\DataLoad\Domain\Entity\File;

interface XmlFileFinderInterface
{
    /**
     * @return list<File>
     */
    public function find(string $token): array;
}

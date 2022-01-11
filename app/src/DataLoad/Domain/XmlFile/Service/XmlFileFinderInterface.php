<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\XmlFile\Service;

use App\DataLoad\Domain\XmlFile\Entity\XmlFile;

interface XmlFileFinderInterface
{
    /**
     * @return list<XmlFile>
     */
    public function find(string $token): array;
}

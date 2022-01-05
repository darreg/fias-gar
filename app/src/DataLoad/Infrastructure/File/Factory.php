<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\File;

use App\DataLoad\Domain\Entity\File;
use App\DataLoad\Infrastructure\ParameterStorage;

class Factory
{
    private string $xmlDirectory;
    private ParameterStorage $parameterStorage;

    public function __construct(
        string $xmlDirectory,
        ParameterStorage $parameterStorage
    ) {
        $this->xmlDirectory = $xmlDirectory;
        $this->parameterStorage = $parameterStorage;
    }

    public function create(string $fileName): File
    {
        $filePath = $this->xmlDirectory . '/' . $fileName;
        $fileToken = File::getFileToken($filePath);

        $tagName = $this->parameterStorage->getTagNameByFileToken($fileToken);

        return new File($filePath, $tagName, $fileToken);
    }
}

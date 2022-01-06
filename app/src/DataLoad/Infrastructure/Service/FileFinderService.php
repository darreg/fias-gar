<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Application\Service\FileFinderInterface;
use App\DataLoad\Domain\Entity\File;
use App\DataLoad\Infrastructure\Exception\XmlDirectoryNotReadableException;
use App\DataLoad\Infrastructure\ParameterStorage;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class FileFinderService implements FileFinderInterface
{
    private string $xmlDirectory;
    private ParameterStorage $parameterStorage;

    public function __construct(string $xmlDirectory, ParameterStorage $parameterStorage)
    {
        $this->xmlDirectory = $xmlDirectory;
        $this->parameterStorage = $parameterStorage;
    }

    /**
     * @throws XmlDirectoryNotReadableException
     * @return list<File>
     */
    public function find(string $token): array
    {
        $tagName = $this->parameterStorage->getTagNameByFileToken($token);
        $finder = new SymfonyFinder();

        $files = [];
        foreach ($this->getFilePathsByToken($token, $finder) as $path) {
            $files[] = new File($path, $tagName, $token);
        }

        return $files;
    }

    /**
     * @throws XmlDirectoryNotReadableException
     * @return list<string>
     */
    private function getFilePathsByToken(string $token, SymfonyFinder $finder): array
    {
        if (!is_dir($this->xmlDirectory) || !is_readable($this->xmlDirectory)) {
            throw new XmlDirectoryNotReadableException('Invalid directory for xml files');
        }

        $pattern = '/^AS_' . $token . '_([0-9]{8})_(?:.*)\.xml$/i';
        $finder->files()->in($this->xmlDirectory)->name($pattern);

        $paths = [];
        foreach ($finder as $file) {
            $paths[] = $file->getPathname();
        }

        return $paths;
    }
}

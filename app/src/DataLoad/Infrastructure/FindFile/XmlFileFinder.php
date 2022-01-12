<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\FindFile;

use App\DataLoad\Domain\Shared\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Domain\XmlFile\Entity\XmlFile;
use App\DataLoad\Domain\XmlFile\Exception\TokenNotRecognizedException;
use App\DataLoad\Domain\XmlFile\Service\XmlFileFinderInterface;
use App\DataLoad\Infrastructure\Shared\ParameterStorage;
use RuntimeException;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class XmlFileFinder implements XmlFileFinderInterface
{
    private string $xmlDirectory;
    private ParameterStorage $parameterStorage;

    public function __construct(string $xmlDirectory, ParameterStorage $parameterStorage)
    {
        $this->xmlDirectory = $xmlDirectory;
        $this->parameterStorage = $parameterStorage;
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @return list<XmlFile>
     */
    public function find(string $token): array
    {
        $tagName = $this->parameterStorage->getTagNameByFileToken($token);
        $finder = new SymfonyFinder();

        $files = [];
        foreach ($this->getFilePathsByToken($token, $finder) as $path) {
            $files[] = new XmlFile($path, $tagName, $token);
        }

        return $files;
    }

    /**
     * @throws RuntimeException
     * @throws DirectoryIsNotReadableException
     * @throws TokenNotRecognizedException
     * @return list<XmlFile>
     */
    public function findAll(): array
    {
        $paths = $this->getAllFindPath();

        $files = [];
        foreach ($paths as $path) {
            $token = $this->getFileNameToken($path);
            $tagName = $this->parameterStorage->getTagNameByFileToken($token);
            $files[] = new XmlFile($path, $tagName, $token);
        }

        return $files;
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @return list<string>
     */
    public function getAllFindPath(): array
    {
        if (!is_readable($this->xmlDirectory)) {
            throw new DirectoryIsNotReadableException("Invalid directory for xml files - {$this->xmlDirectory}");
        }

        $finder = new SymfonyFinder();
        $finder->files()->in($this->xmlDirectory)->name('/\.xml$/i');

        $paths = [];
        foreach ($finder as $file) {
            $paths[] = $file->getPathname();
        }

        return $paths;
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @return list<string>
     */
    private function getFilePathsByToken(string $token, SymfonyFinder $finder): array
    {
        if (!is_readable($this->xmlDirectory)) {
            throw new DirectoryIsNotReadableException("Invalid directory for xml files - {$this->xmlDirectory}");
        }

        $pattern = '/^AS_' . $token . '_([0-9]{8})_(?:.*)\.xml$/i';
        $finder->files()->in($this->xmlDirectory)->name($pattern);

        $paths = [];
        foreach ($finder as $file) {
            $paths[] = $file->getPathname();
        }

        return $paths;
    }

    /**
     * @throws RuntimeException
     * @throws TokenNotRecognizedException
     */
    private function getFileNameToken(string $filePath): string
    {
        $fileName = XmlFile::getBaseName($filePath);

        preg_match('/^AS_(.*?)_\d/i', $fileName, $m);
        if (empty($m[1])) {
            throw new TokenNotRecognizedException("The token is not recognized for the file '{$filePath}'");
        }

        return strtolower($m[1]);
    }
}

<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Shared\Exception\DirectoryIsNotReadableException;
use App\DataLoad\Domain\XmlFile\Entity\XmlFile;
use App\DataLoad\Domain\XmlFile\Exception\TokenNotRecognizedException;
use App\DataLoad\Domain\XmlFile\Service\XmlFileFinderInterface;
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
    public function find(string $versionId, string $token): array
    {
        $tagName = $this->parameterStorage->getTagNameByFileToken($token);
        $finder = new SymfonyFinder();

        $files = [];
        foreach ($this->getFilePathsByToken($versionId, $token, $finder) as $path) {
            $files[] = new XmlFile($path, $tagName, $token);
        }

        return $files;
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @throws TokenNotRecognizedException
     * @throws RuntimeException
     * @return list<XmlFile>
     */
    public function findAll(string $versionId): array
    {
        $paths = $this->getAllFindPathByVersion($versionId);

        $files = [];
        foreach ($paths as $path) {
            $token = $this->getFileNameToken($path);
            $tagName = $this->parameterStorage->getTagNameByFileToken($token);
            $files[] = new XmlFile($path, $tagName, $token);
        }

        return $files;
    }

    public function versionDirectoryExists(string $versionId): bool
    {
        return is_dir($this->getVersionDirectory($versionId));
    }

    public function getVersionDirectory(string $versionId): string
    {
        return $this->xmlDirectory . '/' . $versionId;
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @return list<string>
     */
    public function getAllFindPathByVersion(string $versionId): array
    {
        $xmlDirectory = $this->getVersionDirectory($versionId);

        return $this->getAllFindPath($xmlDirectory);
    }

    /**
     * @throws DirectoryIsNotReadableException
     * @return list<string>
     */
    public function getAllFindPath(string $dir): array
    {
        if (!is_readable($dir)) {
            throw new DirectoryIsNotReadableException("Invalid directory for xml files - {$dir}");
        }

        $finder = new SymfonyFinder();
        $finder->files()->in($dir)->name('/\.xml$/i');

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
    private function getFilePathsByToken(string $versionId, string $token, SymfonyFinder $finder): array
    {
        $xmlDirectory = $this->xmlDirectory . '/' . $versionId;
        if (!is_readable($xmlDirectory)) {
            throw new DirectoryIsNotReadableException("Invalid directory for xml files - {$xmlDirectory}");
        }

        $pattern = '/^AS_' . $token . '_([0-9]{8})_(?:.*)\.xml$/i';
        $finder->files()->in($xmlDirectory)->name($pattern);

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

<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\ZipFile\Exception\FileRemoveException;
use App\DataLoad\Domain\ZipFile\Service\ZipFileRotatorInterface;
use Symfony\Component\Finder\Finder as SymfonyFinder;

class ZipFileRotator implements ZipFileRotatorInterface
{
    private string $zipDirectory;
    private string $fullFileName;
    private string $deltaFileName;
    private int $maxFullFileNum;
    private int $maxDeltaFileNum;

    public function __construct(
        string $zipDirectory,
        string $fullFileName,
        string $deltaFileName,
        int $maxFullFileNum,
        int $maxDeltaFileNum,
    ) {
        $this->zipDirectory = $zipDirectory;
        $this->fullFileName = $fullFileName;
        $this->deltaFileName = $deltaFileName;
        $this->maxFullFileNum = $maxFullFileNum;
        $this->maxDeltaFileNum = $maxDeltaFileNum;
    }

    /**
     * @throws FileRemoveException
     */
    public function rotate(): void
    {
        $paths = $this->getFilePaths();
        if (\count($paths) === 0) {
            return;
        }

        $this->removeDeltaFiles($paths);
        $this->removeFullFiles($paths);
    }

    /**
     * @return list<string> $paths
     */
    private function getFilePaths(): array
    {
        $finder = new SymfonyFinder();
        $finder->files()->in($this->zipDirectory)->name('/\.zip$/i');

        $paths = [];
        foreach ($finder as $file) {
            $paths[] = $file->getPathname();
        }

        sort($paths);

        return $paths;
    }

    /**
     * @param list<string> $paths
     * @throws FileRemoveException
     */
    private function removeDeltaFiles(array $paths): void
    {
        $i = 0;
        foreach ($paths as $path) {
            if (preg_match('/' . $this->deltaFileName . '$/i', $path)) {
                ++$i;
                if ($i >= $this->maxDeltaFileNum) {
                    $this->removeFile($path);
                }
            }
        }
    }

    /**
     * @param list<string> $paths
     * @throws FileRemoveException
     */
    private function removeFullFiles(array $paths): void
    {
        $i = 0;
        foreach ($paths as $path) {
            if (preg_match('/' . $this->fullFileName . '$/i', $path)) {
                ++$i;
                if ($i >= $this->maxFullFileNum) {
                    $this->removeFile($path);
                }
            }
        }
    }

    /**
     * @throws FileRemoveException
     */
    private function removeFile(string $path): void
    {
        $result = unlink($path);
        if (!$result) {
            throw new FileRemoveException("Failed to delete file '{$path}'");
        }
    }
}

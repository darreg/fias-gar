<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Entity;

use DomainException;

class File
{
    private string $name;
    private string $token;
    private string $path;
    private string $version;
    private string $tagName;

    public function __construct(
        string $path,
        string $tagName,
        ?string $token = null,
        ?string $name = null,
        ?string $version = null
    ) {
        $this->path = $path;
        $this->tagName = $tagName;

        $this->token = $token ?? self::getFileToken($path);
        $this->name = $name ?? self::getBaseName($path);
        $this->version = $version ?? self::getFileVersion($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    /**
     * @throws DomainException
     */
    public static function getBaseName(string $filePath): string
    {
        if ($filePath === '') {
            throw new DomainException('The file path is not specified');
        }

        $pathInfo = pathinfo($filePath);
        if (empty($pathInfo['basename'])) {
            throw new DomainException('The file path was not recognized' . $filePath);
        }

        return $pathInfo['basename'];
    }

    /**
     * @throws DomainException
     */
    public static function getFileToken(string $filePath): string
    {
        $fileName = self::getBaseName($filePath);

        preg_match('/^AS_(.*?)_\d/i', $fileName, $m);
        if (empty($m[1])) {
            throw new DomainException('The file token was not recognized' . $filePath);
        }

        return strtolower($m[1]);
    }

    /**
     * @throws DomainException
     */
    public static function getFileVersion(string $filePath): string
    {
        $fileName = self::getBaseName($filePath);

        preg_match('/^AS_(?:.*)_([0-9]{8})_/i', $fileName, $m);
        if (empty($m[1])) {
            throw new DomainException('The file version was not recognized' . $filePath);
        }

        $year = substr($m[1], 0, 4);
        $month = substr($m[1], 4, 2);
        $date = substr($m[1], 6, 2);
        return $year . '-' . $month . '-' . $date;
    }
}

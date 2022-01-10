<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Entity;

use App\DataLoad\Domain\Exception\TokenNotRecognizedException;
use App\DataLoad\Domain\Exception\VersionNotRecognizedException;
use DateTimeImmutable;
use RuntimeException;
use Webmozart\Assert\Assert;

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
        Assert::notEmpty($path);
        Assert::notEmpty($tagName);

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
     * @throws RuntimeException
     */
    public static function getBaseName(string $filePath): string
    {
        $pathInfo = pathinfo($filePath);
        if (empty($pathInfo['basename'])) {
            throw new RuntimeException("The file path '{$filePath}' was not recognized");
        }

        return $pathInfo['basename'];
    }

    /**
     * @throws TokenNotRecognizedException
     * @throws RuntimeException
     */
    public static function getFileToken(string $filePath): string
    {
        $fileName = self::getBaseName($filePath);

        preg_match('/^AS_(.*?)_\d/i', $fileName, $m);
        if (empty($m[1])) {
            throw new TokenNotRecognizedException("The file token for '{$filePath}' was not recognized");
        }

        return strtolower($m[1]);
    }

    /**
     * @throws VersionNotRecognizedException
     * @throws RuntimeException
     */
    public static function getFileVersion(string $filePath): string
    {
        $fileName = self::getBaseName($filePath);

        preg_match('/^AS_(?:.*)_([0-9]{8})_/i', $fileName, $m);
        if (empty($m[1])) {
            throw new VersionNotRecognizedException("The file version for '{$filePath}' was not recognized");
        }

        return self::convertVersion($m[1], 'd-m-Y');
    }

    /**
     * @throws VersionNotRecognizedException
     */
    public static function convertVersion(string $versionId, string $format = 'Y.m.d'): string
    {
        $date = DateTimeImmutable::createFromFormat('Ymd', $versionId);
        if (!$date || !preg_match('/^\d{8}$/', $versionId)) {
            throw new VersionNotRecognizedException("Version '{$versionId}' has the wrong format");
        }

        return $date->format($format);
    }
}

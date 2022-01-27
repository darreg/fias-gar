<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\ZipFile\Exception\FileNotAvailableException;
use App\DataLoad\Domain\ZipFile\Service\ZipFileLoaderInterface;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ZipFileLoader implements ZipFileLoaderInterface
{
    private string $zipDirectory;
    private bool $reloadIfExists;

    public function __construct(
        string $zipDirectory,
        bool $reloadIfExists
    ) {
        $this->zipDirectory = $zipDirectory;
        $this->reloadIfExists = $reloadIfExists;
    }

    /**
     * @throws FileNotAvailableException
     * @throws RuntimeException
     */
    public function load(string $url, string $versionId): string
    {
        $urlPath = parse_url($url, PHP_URL_PATH);
        $fileName = $versionId . '_' . basename($urlPath);
        $filePath = $this->zipDirectory . '/' . $fileName;

        if (!$this->reloadIfExists && file_exists($filePath)) {
            return $fileName;
        }

        if (!self::testFileUrl($url)) {
            throw new FileNotAvailableException("The file on the link '{$url}' is not available");
        }

        try {
            $fp = fopen($filePath, 'wb');
            self::loadFile($url, $fp);
            fclose($fp);
        } catch (Exception $e) {
            throw new RuntimeException('Zip file download error', 0, $e);
        }

        return $fileName;
    }

    /**
     * @psalm-suppress MissingParamType
     * @param mixed $fp
     */
    private static function loadFile(string $url, $fp): void
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }

    private static function testFileUrl(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        if (@curl_errno($ch)) {
            @curl_close($ch);
            return false;
        }

        /** @var string $code */
        $code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (int)$code === Response::HTTP_OK;
    }
}

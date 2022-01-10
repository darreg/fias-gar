<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use Exception;
use RuntimeException;

class ZipFileLoader
{
    private string $zipDirectory;

    public function __construct(
        string $zipDirectory
    ) {
        $this->zipDirectory = $zipDirectory;
    }

    public function load(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $fileName = $this->zipDirectory . '/' . basename($path);

        try {
            $fp = fopen($fileName, 'wb');
            self::useCurl($url, $fp);
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
    private static function useCurl(string $url, $fp): void
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
    }
}

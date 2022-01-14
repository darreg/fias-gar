<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Version\Entity\Version;
use App\DataLoad\Domain\Version\Service\VersionDecoderInterface;
use DateTimeImmutable;
use Exception;
use RuntimeException;

class VersionDecoder implements VersionDecoderInterface
{
    /**
     * @throws RuntimeException
     */
    public function decode(string $versionsString): array
    {
        try {
            $versionsData = json_decode($versionsString, true, 512, JSON_THROW_ON_ERROR);
            if (!\is_array($versionsData)) {
                throw new RuntimeException('The list of versions is not an array');
            }

            $versions = [];
            foreach ($versionsData as $versionData) {
                if (!\is_array($versionData)) {
                    throw new RuntimeException('Version data is not an array');
                }

                $versionId = (string)$versionData['VersionId'];
                $title = (string)$versionData['TextVersion'];

                $date = DateTimeImmutable::createFromFormat('Ymd', $versionId);
                if (!$date) {
                    throw new RuntimeException('Version date decoding error');
                }

                $versions[$versionId] = new Version(
                    $versionId,
                    $title,
                    $date,
                    !empty($versionData['GarXMLFullURL']),
                    !empty($versionData['GarXMLDeltaURL']),
                );
            }

            return $versions;
        } catch (Exception $e) {
            throw new RuntimeException('Json decoding error', 0, $e);
        }
    }
}

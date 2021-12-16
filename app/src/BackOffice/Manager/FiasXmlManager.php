<?php

declare(strict_types=1);

namespace App\BackOffice\Manager;

use App\BackOffice\Exception\FiasImportException;

final class FiasXmlManager
{
    public static function parse(string $tagXml): array
    {
        $xmlElement = simplexml_load_string($tagXml);
        if (empty($xmlElement)) {
            throw new FiasImportException('Не удалось разобрать xml');
        }

        $tagData = (array)$xmlElement;
        if (empty($tagData['@attributes'])) {
            throw new FiasImportException('Не удалось получить значения аттрибутов тега');
        }

        return (array)$tagData['@attributes'];
    }
}

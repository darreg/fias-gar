<?php
declare(strict_types=1);

namespace App\Service\FiasImport;


class FiasImportXmlService
{
    public static function parse(string $tagXml): ?array
    {
        $xmlElement = simplexml_load_string($tagXml);
        if (empty($xmlElement)) {
            throw new FiasImportException('Не удалось разобрать xml');
        }

        $tagData = (array)$xmlElement;
        if (empty($tagData['@attributes'])) {
            throw new FiasImportException('Не удалось получить значения аттрибутов тега');
        }

        return $tagData['@attributes'];
    }
}

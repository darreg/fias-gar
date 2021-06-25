<?php

namespace App\Service;

use App\DTO\FiasParseDTO;
use App\Exception\FiasImportException;
use App\Manager\FiasDbManager;
use App\Manager\FiasFileManager;

final class FiasImportService
{
    private FiasFileManager $fiasFileManager;
    private FiasDbManager $fiasDbManager;
    private AsyncService $asyncService;

    public function __construct(
        FiasFileManager $fiasFileManager,
        FiasDbManager $fiasDbManager,
        AsyncService $asyncService
    ) {
        $this->fiasFileManager = $fiasFileManager;
        $this->fiasDbManager = $fiasDbManager;
        $this->asyncService = $asyncService;
    }

    /**
     * @throws FiasImportException
     */
    public function import(string $fileName): int
    {
        $token = $this->fiasFileManager->getFileNameToken($fileName);
        if ($token === null) {
            throw new FiasImportException('Не найден токен файла ' . $fileName);
        }

        $fileNames = $this->fiasFileManager->getFileNamesByToken($token);
        $i = 0;
        foreach ($fileNames as $name) {
            $i += $this->importFile($name);
        }

        return $i;
    }

    /**
     * @throws FiasImportException
     */
    public function importFile(string $filePath): int
    {
        if (empty($filePath)) {
            throw new FiasImportException('Не найден файл ' . $filePath);
        }

        $token = $this->fiasFileManager->getFileNameToken($filePath);
        if ($token === null) {
            throw new FiasImportException('Не найден токен файла ' . $filePath);
        }

        $tagName = $this->fiasFileManager->getTagNameByFile($token);
        if ($tagName === null) {
            throw new FiasImportException('Не найдено имя xml-тега для файла ' . $filePath);
        }

        $tableName = $this->fiasFileManager->getTableNameByFile($token);
        if ($tableName === null) {
            throw new FiasImportException('Не найдено имя таблицы для файла ' . $filePath);
        }

        $primaryKeyName = $this->fiasFileManager->getPrimaryKeyNameByFile($token);

        $tableColumnNamesAsString = $this->fiasDbManager->getTableColumnsAsString($tableName);
        if ($tableColumnNamesAsString === null) {
            throw new FiasImportException('Не найдены поля таблицы для файла ' . $filePath);
        }

        $i = 0;
        $xmlTags = $this->fiasFileManager->xmlTagGenerator($filePath, $tagName);
        if ($xmlTags === null) {
            throw new FiasImportException('Не найдены теги в файле ' . $filePath);
        }

        /** @var string $xmlTag */
        foreach ($xmlTags as $xmlTag) {
            if (empty($xmlTag)) {
                continue;
            }
            $message = (new FiasParseDTO(
                $token,
                $tableName,
                $primaryKeyName,
                $tableColumnNamesAsString,
                $xmlTag
            ))->toAMQPMessage();
            $this->asyncService->publishToExchange(AsyncService::PARSE, $message);
            $i++;
        }

        return $i;
    }
}

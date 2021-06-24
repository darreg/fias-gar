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
        $tagName = $this->fiasFileManager->getTagNameByFile($token);
        $tableName = $this->fiasFileManager->getTableNameByFile($token);
        $primaryKeyName = $this->fiasFileManager->getPrimaryKeyNameByFile($token);
        $tableColumnNamesAsString = $this->fiasDbManager->getTableColumnsAsString($tableName);

        $i = 0;
        foreach ($this->fiasFileManager->xmlTagGenerator($filePath, $tagName) as $xmlTag) {
            $message = (new FiasParseDTO(
                $token,
                $tableName,
                $primaryKeyName,
                $tableColumnNamesAsString,
                $xmlTag
            )
            )->toAMQPMessage();
            $result = $this->asyncService->publishToExchange(AsyncService::PARSE, $message);
            $i++;
        }

        return $i;
    }
}

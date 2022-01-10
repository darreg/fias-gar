<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Infrastructure\Exception\ConfigParameterNotFoundException;
use App\DataLoad\Infrastructure\Exception\TableNameNotFoundException;
use App\DataLoad\Infrastructure\Exception\TagNameNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ParameterStorage
{
    public const TABLES_PKEY = 'fias_tables_pkey';
    public const TABLES = 'fias_tables';
    public const TAGS = 'fias_tags';

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getPrimaryKeyByFileToken(string $fileToken): string
    {
        /** @var array<string, string> $fiasTablePkeys */
        $fiasTablePkeys = $this->parameterBag->get(self::TABLES_PKEY);
        if (empty($fiasTablePkeys[$fileToken])) {
            return 'id';
        }

        return $fiasTablePkeys[$fileToken];
    }

    /**
     * @throws TableNameNotFoundException
     */
    public function getTableNameByFileToken(string $fileToken): string
    {
        /** @var array<string, string> $fiasTables */
        $fiasTables = $this->parameterBag->get(self::TABLES);
        if (empty($fiasTables[$fileToken])) {
            throw new TableNameNotFoundException("No table name found for the token '{$fileToken}'");
        }

        return $fiasTables[$fileToken];
    }

    /**
     * @throws TagNameNotFoundException
     */
    public function getTagNameByFileToken(string $fileToken): string
    {
        /** @var array<string, string> $fiasTags */
        $fiasTags = $this->parameterBag->get(self::TAGS);
        if (empty($fiasTags[$fileToken])) {
            throw new TagNameNotFoundException("No tag name found for the token '{$fileToken}'");
        }

        return $fiasTags[$fileToken];
    }

    public function getParameter(string $name): string
    {
        /**
         * @var string $value
         */
        $value = $this->parameterBag->get($name);
        if (!$value) {
            throw new ConfigParameterNotFoundException("Config parameter '{$name}' not found");
        }

        return $value;
    }
}

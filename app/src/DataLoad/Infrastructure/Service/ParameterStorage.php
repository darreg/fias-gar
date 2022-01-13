<?php

declare(strict_types=1);

namespace App\DataLoad\Infrastructure\Service;

use App\DataLoad\Domain\Table\Exception\TableNameNotFoundException;
use App\DataLoad\Domain\Tag\Exception\TagNameNotFoundException;
use App\DataLoad\Infrastructure\Exception\ConfigParameterNotFoundException;
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
        $fiasTablePkeys = $this->getParameter(self::TABLES_PKEY);
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
        $fiasTables = $this->getParameter(self::TABLES);
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
        $fiasTags = $this->getParameter(self::TAGS);
        if (empty($fiasTags[$fileToken])) {
            throw new TagNameNotFoundException("No tag name found for the token '{$fileToken}'");
        }

        return $fiasTags[$fileToken];
    }

    /**
     * @throws ConfigParameterNotFoundException
     */
    private function getParameter(string $name): array
    {
        $value = $this->parameterBag->get($name);
        if (!$value) {
            throw new ConfigParameterNotFoundException("Config parameter '{$name}' not found");
        }
        if (!\is_array($value)) {
            throw new ConfigParameterNotFoundException("Only scalar parameter '{$name}' was found");
        }

        return $value;
    }
}

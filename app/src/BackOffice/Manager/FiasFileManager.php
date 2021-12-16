<?php

declare(strict_types=1);

namespace App\BackOffice\Manager;

use App\BackOffice\Exception\FiasImportException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

final class FiasFileManager
{
    public const BUFFER_SIZE = 10000;
    public const SCHEMAS_DIR = 'Schemas';

    private ParameterBagInterface $parameterBag;
    private string $fiasXmlDirectory;

    public function __construct(
        ParameterBagInterface $parameterBag,
        string $fiasXmlDirectory
    ) {
        $this->parameterBag = $parameterBag;
        $this->fiasXmlDirectory = $fiasXmlDirectory;
    }

    /**
     * @throws FiasImportException
     */
    public function xmlTagGenerator(string $filePath, string $tagName): ?\Generator
    {
        if (empty($filePath)) {
            throw new FiasImportException('Не указан путь к xml файлу');
        }
        if (empty($tagName)) {
            throw new FiasImportException('Не указано имя тега');
        }

        $fh = fopen($filePath, 'rb');
        if ($fh === false) {
            throw new FiasImportException('Не удалось открыть файл ' . $filePath);
        }

        $tagBegin = '<' . $tagName . ' ';
        $tagEnd = '/>';
        $tagEndLength = \strlen($tagEnd);

        $buffer = '';
        $buffer .= fread($fh, self::BUFFER_SIZE);

        $startPosition = strpos($buffer, $tagBegin);
        if ($startPosition === false) {
            fclose($fh);
            return null;
        }

        $buffer = substr($buffer, $startPosition);
        while (true) {
            $endPosition = strpos($buffer, $tagEnd);
            while ($endPosition === false) {
                $buffer .= fread($fh, self::BUFFER_SIZE);
                $endPosition = strpos($buffer, $tagEnd);
                if ($endPosition === false && feof($fh)) {
                    break 2;
                }
            }
            /** @psalm-suppress PossiblyFalseOperand */
            $endPosition += $tagEndLength;

            yield substr($buffer, 0, $endPosition);

            $buffer = substr($buffer, $endPosition);
        }

        fclose($fh);
    }

    public function getFiles(): array
    {
        if ($this->fiasXmlDirectory === '') {
            throw new FiasImportException('Некорректная папка с xml-файлами');
        }

        $files = [];

        $finder = new Finder();
        $finder->files()->in($this->fiasXmlDirectory)->name('/\.xml$/i');
        /** @var array<array-key, string> $finder */
        foreach ($finder as $file) {
            $files[] = $file;
        }

        return $files;
    }

    public function getFile(string $fileName): ?string
    {
        if ($this->fiasXmlDirectory === '') {
            throw new FiasImportException('Некорректная папка с xml-файлами');
        }

        $finder = new Finder();
        $finder->files()->in($this->fiasXmlDirectory)->name($fileName);
        if (!$finder->hasResults()) {
            return null;
        }

        $files = iterator_to_array($finder);
        return (string)reset($files);
    }

    /**
     * @return array<int, string>
     */
    public function getFileNamesByToken(string $token): array
    {
        if ($this->fiasXmlDirectory === '') {
            throw new FiasImportException('Некорректная папка с xml-файлами');
        }

        $files = [];

        $finder = new Finder();

        $pattern = '/^AS_' . $token . '_([0-9]{8})_(?:.*)\.xml$/i';

        $finder->files()->in($this->fiasXmlDirectory)->name($pattern);
        /** @var array<array-key, string> $finder */
        foreach ($finder as $file) {
            $files[] = $file;
        }

        return $files;
    }

    /**
     * @throws FiasImportException
     */
    public function unzip(string $fileName): void
    {
        if ($this->fiasXmlDirectory === '') {
            throw new FiasImportException('Некорректная папка с xml-файлами');
        }

        $files = $this->getFiles();
        if (!empty($files)) {
            throw new FiasImportException('Не удалены старые XML файлы');
        }

        $filePath = $this->fiasXmlDirectory . '/' . $fileName;
        if (!file_exists($filePath)) {
            throw new FiasImportException('Не найден файл ' . $fileName);
        }

        exec('unzip  ' . $filePath . ' -d ' . $this->fiasXmlDirectory, $output, $exitCode);
        if ($exitCode != 0) {
            throw new FiasImportException('Возникла ошибка при разархивировании');
        }

        $files = $this->getFiles();
        if (empty($files)) {
            throw new FiasImportException('Не найдены XML файлы после распаковки архива');
        }

        exec('chmod 777 ' . $this->fiasXmlDirectory . '/' . self::SCHEMAS_DIR);
        exec('chmod -R 644 ' . $this->fiasXmlDirectory . '/*.XML');
    }

    public function clear(): void
    {
        if ($this->fiasXmlDirectory === '') {
            throw new FiasImportException('Некорректная папка с xml-файлами');
        }

        exec('rm -rf ' . $this->fiasXmlDirectory . '/*');
    }

    public function getPrimaryKeyNameByFile(string $token): string
    {
        /** @var array<string, string> $fiasTablePkeys */
        $fiasTablePkeys = $this->parameterBag->get('fias_tables_pkey');
        if (empty($fiasTablePkeys[$token])) {
            return 'id';
        }

        return $fiasTablePkeys[$token];
    }

    public function getTableNameByFile(string $token): ?string
    {
        /** @var array<string, string> $fiasTables */
        $fiasTables = $this->parameterBag->get('fias_tables');
        if (empty($fiasTables[$token])) {
            return null;
        }

        return $fiasTables[$token];
    }

    public function getTagNameByFile(string $token): ?string
    {
        /** @var array<string, string> $fiasTags */
        $fiasTags = $this->parameterBag->get('fias_tags');
        if (empty($fiasTags[$token])) {
            return null;
        }

        return $fiasTags[$token];
    }

    public function getBaseName(string $filePath): ?string
    {
        if (empty($filePath)) {
            throw new FiasImportException('Не указан путь к xml файлу');
        }

        $pathInfo = pathinfo($filePath);
        if (empty($pathInfo['basename'])) {
            throw new FiasImportException('Не распознан переданный путь к файлу ' . $filePath);
        }

        return $pathInfo['basename'];
    }

    /**
     * @throws FiasImportException
     */
    public function getFileNameToken(string $filePath): ?string
    {
        $fileName = $this->getBaseName($filePath);
        if ($fileName === null) {
            return null;
        }

        preg_match('/^AS_(.*?)_\d/i', $fileName, $m);
        if (!empty($m[1])) {
            return strtolower($m[1]);
        }

        return null;
    }

    /**
     * @throws FiasImportException
     */
    public function getFileVersion(string $filePath): ?string
    {
        $fileName = $this->getBaseName($filePath);
        if ($fileName === null) {
            return null;
        }

        preg_match('/^AS_(?:.*)_([0-9]{8})_/i', $fileName, $m);
        if (!empty($m[1])) {
            $year = substr($m[1], 0, 4);
            $month = substr($m[1], 4, 2);
            $date = substr($m[1], 6, 2);
            return  $year . '-' . $month . '-' . $date;
        }

        return null;
    }
}

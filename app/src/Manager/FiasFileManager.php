<?php

declare(strict_types=1);

namespace App\Manager;

use App\Exception\FiasImportException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

final class FiasFileManager
{
    public const BUFFER_SIZE = 10000;
    public const SCHEMAS_DIR = 'Schemas';

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
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
            $endPosition += $tagEndLength;

            yield substr($buffer, 0, $endPosition);

            $buffer = substr($buffer, $endPosition);
            $startPosition += $endPosition;
        }

        fclose($fh);
    }

    public function getFiles(): array
    {
        $files = [];

        $fiasXmlDirectory = $this->parameterBag->get('fias_xml_directory');
        $finder = new Finder();
        $finder->files()->in($fiasXmlDirectory)->name('/\.xml$/i');
        foreach ($finder as $file) {
            $files[] = $file;
        }

        return $files;
    }

    public function getFile(string $fileName): ?string
    {
        $fiasXmlDirectory = $this->parameterBag->get('fias_xml_directory');
        $finder = new Finder();
        $finder->files()->in($fiasXmlDirectory)->name($fileName);
        if (!$finder->hasResults()) {
            return null;
        }

        $files = iterator_to_array($finder);
        return (string)reset($files);
    }

    public function getFileNamesByToken(string $token): array
    {
        $files = [];

        $fiasXmlDirectory = $this->parameterBag->get('fias_xml_directory');
        $finder = new Finder();

        $pattern = '/^AS_' . $token . '_([0-9]{8})_(?:.*)\.xml$/i';

        $finder->files()->in($fiasXmlDirectory)->name($pattern);
        foreach ($finder as $file) {
            $files[] = (string)$file;
        }

        return $files;
    }

    /**
     * @throws FiasImportException
     */
    public function unzip(string $fileName): void
    {
        $files = $this->getFiles();
        if (!empty($files)) {
            throw new FiasImportException('Не удалены старые XML файлы');
        }

        $fiasXmlDirectory = $this->parameterBag->get('fias_xml_directory');
        $filePath = $fiasXmlDirectory . '/' . $fileName;
        if (!file_exists($filePath)) {
            throw new FiasImportException('Не найден файл ' . $fileName);
        }

        exec('unzip  ' . $filePath . ' -d ' . $fiasXmlDirectory, $output, $exitCode);
        if ($exitCode != 0) {
            throw new FiasImportException('Возникла ошибка при разархивировании');
        }

        $files = $this->getFiles();
        if (empty($files)) {
            throw new FiasImportException('Не найдены XML файлы после распаковки архива');
        }

        shell_exec('chmod 777 ' . $fiasXmlDirectory . '/' . self::SCHEMAS_DIR);
        shell_exec('chmod -R 644 ' . $fiasXmlDirectory . '/*.XML');
    }

    public function clear(): void
    {
        $fiasXmlDirectory = $this->parameterBag->get('fias_xml_directory');
        shell_exec('rm -rf ' . $fiasXmlDirectory . '/*');
    }

    public function getPrimaryKeyNameByFile(string $token): ?string
    {
        $fiasTablePkeys = $this->parameterBag->get('fias_tables_pkey');
        if (empty($fiasTablePkeys[$token])) {
            return 'id';
        }

        return $fiasTablePkeys[$token];
    }

    public function getTableNameByFile(string $token): ?string
    {
        $fiasTables = $this->parameterBag->get('fias_tables');
        if (empty($fiasTables[$token])) {
            return null;
        }

        return $fiasTables[$token];
    }

    public function getTagNameByFile(string $token): ?string
    {
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

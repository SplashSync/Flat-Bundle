<?php

namespace Splash\Connectors\Flat\FileParser;

use Splash\Client\Splash;

/**
 * Parser for Csv Files
 */
class CsvParser implements FileParserInterface
{
    /**
     * CSV Separator
     *
     * @var string
     */
    protected static string $separator = ",";

    /**
     * @inheritDoc
     */
    public function code(): string
    {
        return 'csv';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "CSV Standard Files Parser";
    }

    /**
     * @inheritDoc
     */
    public function handle(string $extension): bool
    {
        return in_array($extension, array("csv", ".csv"));
    }

    /**
     * @inheritDoc
     */
    public function parse(string $contents): ?array
    {
        try {
            return self::toAssociative(
                self::toRows($contents)
            );
        } catch (\Throwable $ex) {
            Splash::log()->err(
                sprintf("Unable to parse CSV contents: %s", $ex->getMessage())
            );
        }

        return null;
    }

    /**
     * Convert Raw CSv File to an array of Rows
     */
    protected static function toRows(string $contents): array
    {
        $fp = fopen('php://temp','r+');
        $rows = array();
        fwrite($fp, $contents);
        rewind($fp); //rewind to process CSV
        while (($row = fgetcsv($fp, 0, static::$separator)) !== FALSE) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Combine Header & Rows to build an Associative Array
     */
    protected static function toAssociative(array $rows): ?array
    {
        $header = array_shift($rows);
        $csv    = array();
        foreach($rows as $row) {
            $csv[] = array_combine($header, $row);
        }

        return $csv;
    }
}
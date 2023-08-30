<?php

namespace Splash\Connectors\Flat\FileParser;

use Splash\Client\Splash;

/**
 * Parser for Csv Files
 */
class CsvSemicolonParser extends CsvParser
{
    /**
     * CSV Separator
     *
     * @var string
     */
    protected static string $separator = ";";

    /**
     * @inheritDoc
     */
    public function code(): string
    {
        return 'csv-semicolon';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "CSV Semicolon Files Parser";
    }
}
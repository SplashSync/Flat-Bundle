<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Flat\FileParser;

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

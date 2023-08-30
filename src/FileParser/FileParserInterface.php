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

interface FileParserInterface
{
    /**
     * Get Parser Code
     *
     * @return string
     */
    public function code(): string;

    /**
     * Get Parser Name
     *
     * @return string
     */
    public function name(): string;

    /**
     * Check if this Parser Handle this kind of file
     *
     * @param string $extension
     *
     * @return bool
     */
    public function handle(string $extension): bool;

    /**
     * Parse Raw File Contents
     *
     * @param string $contents
     *
     * @return null|array<int, array<string, mixed>>
     */
    public function parse(string $contents): ?array;
}

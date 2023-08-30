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

namespace Splash\Connectors\Flat\FileReader;

/**
 * Files Reader Interface, Used to read files raw contents from RLocal or Remotes Locations
 */
interface FileReaderInterface
{
    /**
     * Get Adapter Code
     *
     * @return string
     */
    public function code(): string;

    /**
     * Get Adapter Name
     *
     * @return string
     */
    public function name(): string;

    /**
     * Check if this Reader Handle this kind of urls
     *
     * @param string $scheme
     *
     * @return bool
     */
    public function handle(string $scheme): bool;

    /**
     * Validate User Adapter Configuration
     *
     * @param string $url
     *
     * @return bool
     */
    public function validate(string $url): bool;

    /**
     * Build  Gaufrette Adapter & Return raw file contents
     *
     * @param string $url
     *
     * @return null|string
     */
    public function read(string $url): ?string;
}

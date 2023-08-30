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

use Splash\Client\Splash;
use Splash\Connectors\Flat\Helpers\UrlAnalyser;

/**
 * Read Files from Raw Url using PHP
 */
class Http implements FileReaderInterface
{
    //====================================================================//
    // File Reader Implementation
    //====================================================================//

    /**
     * @inheritDoc
     */
    public function code(): string
    {
        return "http";
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "Http / HTTPS files reader";
    }

    /**
     * @inheritDoc
     */
    public function validate(string $url): bool
    {
        //====================================================================//
        // Verify Host
        if (empty(UrlAnalyser::getHost($url))) {
            Splash::log()->err(sprintf("Invalid Url, %s has no host", $url));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function handle(string $scheme): bool
    {
        return in_array(strtolower($scheme), array("http", "https"), true);
    }

    /**
     * @inheritDoc
     */
    public function read(string $url): ?string
    {
        if (!$this->validate($url)) {
            return null;
        }

        return file_get_contents($url, false) ?: null;
    }
}

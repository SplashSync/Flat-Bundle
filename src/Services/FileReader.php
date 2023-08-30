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

namespace Splash\Connectors\Flat\Services;

use Splash\Connectors\Flat\FileReader\FileReaderInterface;
use Splash\Connectors\Flat\Helpers\UrlAnalyser;
use Splash\Core\SplashCore as Splash;

/**
 * Manage Access to File Download Adapters
 */
class FileReader
{
    /**
     * @param FileReaderInterface[] $fileReaders
     */
    public function __construct(private iterable $fileReaders)
    {
    }

    /**
     * Validate Object Target Adapter Url
     */
    public function validate(string $objectName, mixed $url) : bool
    {
        //====================================================================//
        // Verify Target Url
        if (empty($url) || !is_string($url)) {
            return Splash::log()->err(sprintf("[%s] Remote file Url must be a string", $objectName));
        }
        //====================================================================//
        // Verify Adapter
        $scheme = UrlAnalyser::getScheme($url);
        foreach ($this->fileReaders as $factory) {
            if (!$scheme || !$factory->handle($scheme)) {
                continue;
            }

            return $factory->validate($url);
        }

        return Splash::log()->err(sprintf("[%s] File Reader %s not found", $objectName, $scheme));
    }

    /**
     * Read Target file from Url
     */
    public function read(string $url) : ?string
    {
        //====================================================================//
        // Verify Target Url
        if (empty($url)) {
            return null;
        }
        //====================================================================//
        // Find Adapter
        $scheme = UrlAnalyser::getScheme($url);
        foreach ($this->fileReaders as $fileReader) {
            if (!$scheme || !$fileReader->handle($scheme)) {
                continue;
            }

            return $fileReader->read($url);
        }

        return Splash::log()->errNull(sprintf("[%s] Read Fail: File Reader not found", $url));
    }
}

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

use Gaufrette\Adapter;
use Splash\Client\Splash;
use Splash\Connectors\Flat\Helpers\UrlAnalyser;

/**
 * Read Files from FTP servers using Gaufrette FTP Adapter
 */
class Ftp implements FileReaderInterface
{
    //====================================================================//
    // File Reader Implementation
    //====================================================================//

    /**
     * @inheritDoc
     */
    public function code(): string
    {
        return "ftp";
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "FTP files reader";
    }

    /**
     * @inheritDoc
     */
    public function validate(string $url): bool
    {
        //====================================================================//
        // Verify Host
        $host = UrlAnalyser::getHost($url);
        if (empty($host)) {
            Splash::log()->err(sprintf("[FTP] %s: host is not defined", $url));
        }
        //====================================================================//
        // Verify Path
        $path = UrlAnalyser::getPath($url);
        if (empty($path)) {
            Splash::log()->err(sprintf("[FTP] %s: path is not defined", $url));
        }
        //====================================================================//
        // Verify Filename
        if (empty(basename((string) $path))) {
            Splash::log()->err(sprintf("[FTP] %s: filename is not defined", $url));
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function handle(string $scheme): bool
    {
        return $this->code() == $scheme;
    }

    /**
     * @inheritDoc
     */
    public function read(string $url): ?string
    {
        if (!($adapter = $this->getAdapter($url))) {
            return null;
        }
        $path = UrlAnalyser::getPath($url);
        if (!$path || !$adapter->exists($path)) {
            return null;
        }

        return (string) $adapter->read($path) ?: null;
    }

    //====================================================================//
    // Private Methods
    //====================================================================//

    /**
     * @param string $url
     *
     * @return null|Adapter
     */
    protected function getAdapter(string $url): ?Adapter
    {
        if (!$this->validate($url)) {
            return Splash::log()->errNull(sprintf("[%s] FTP Url is invalid", $url));
        }

        return new Adapter\Ftp(
            "/",
            (string) UrlAnalyser::getHost($url),
            array(
                'port' => (int) UrlAnalyser::getPort($url) ?: 21,
                'username' => UrlAnalyser::getUser($url),
                'password' => UrlAnalyser::getPass($url),
                'passive' => true,
            )
        );
    }
}

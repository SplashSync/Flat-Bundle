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

namespace Splash\Connectors\Flat\Helpers;

class UrlAnalyser
{
    /**
     * Extract Scheme from Url
     */
    public static function getScheme(string $url): ?string
    {
        return ((string) parse_url($url, PHP_URL_SCHEME)) ?: null;
    }

    /**
     * Extract Host from Url
     */
    public static function getHost(string $url): ?string
    {
        return ((string) parse_url($url, PHP_URL_HOST)) ?: null;
    }

    /**
     * Extract Path from Url
     */
    public static function getPath(string $url): ?string
    {
        return (string) parse_url($url, PHP_URL_PATH) ?: null;
    }

    /**
     * Extract Post from Url
     */
    public static function getPort(string $url): ?int
    {
        return ((int) parse_url($url, PHP_URL_PORT)) ?: null;
    }

    /**
     * Extract User from Url
     */
    public static function getUser(string $url): ?string
    {
        return ((string) parse_url($url, PHP_URL_USER)) ?: null;
    }

    /**
     * Extract Password from Url
     */
    public static function getPass(string $url): ?string
    {
        return ((string) parse_url($url, PHP_URL_PASS)) ?: null;
    }

    /**
     * Extract Path Extension from Url
     *
     * @param string $url
     *
     * @return null|string
     */
    public static function getExtension(string $url): ?string
    {
        $path = self::getPath($url);

        return $path ? pathinfo($path, PATHINFO_EXTENSION) : null;
    }

    /**
     * Extract RealPath from Url
     */
    public static function getRealPath(string $url, ?string $projectDir = null): ?string
    {
        return realpath((string) self::getPath($url))
            ?: realpath($projectDir."/".((string) self::getPath($url)))
            ?: null
        ;
    }
}

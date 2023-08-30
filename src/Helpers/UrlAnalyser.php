<?php

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
     * @return string|null
     */
    public static function getExtension(string $url): ?string
    {
        $path = self::getPath($url);

        return $path ? pathinfo($path, PATHINFO_EXTENSION) : null;
    }

    /**
     * Extract RealPath from Url
     */
    public static function getRealPath(string $url): ?string
    {
        return realpath((string) self::getPath($url)) ?: null;
    }
}
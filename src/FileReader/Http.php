<?php

namespace Splash\Connectors\Flat\FileReader;

use Gaufrette\Adapter;
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
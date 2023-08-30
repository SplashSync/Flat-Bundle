<?php

namespace Splash\Connectors\Flat\FileReader;

use Gaufrette\Adapter;
use Splash\Client\Splash;
use Splash\Connectors\Flat\Helpers\UrlAnalyser;

/**
 * Read Files from Local System using Gaufrette Local Adapter
 */
class Local implements FileReaderInterface
{
    //====================================================================//
    // File Reader Implementation
    //====================================================================//

    /**
     * @inheritDoc
     */
    public function code(): string
    {
        return "file";
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "Local files reader";
    }

    /**
     * @inheritDoc
     */
    public function validate(string $url): bool
    {
        //====================================================================//
        // Verify File Exists
        $path = UrlAnalyser::getRealPath($url);
        if (empty($path)) {
            Splash::log()->err(sprintf("File %s was not found on local machine", $url));
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
        if (!($path = UrlAnalyser::getRealPath($url))) {
            return null;
        }

        return $adapter->read(basename($path)) ?: null;
    }

    //====================================================================//
    // Private Methods
    //====================================================================//

    /**
     * @param string $url
     *
     * @return Adapter|null
     */
    private function getAdapter(string $url): ?Adapter
    {
        $path = UrlAnalyser::getRealPath($url);
        if ($path && !$this->validate($url)) {
            return Splash::log()->errNull(sprintf("[%s] Read Fail: File not found", $url));
        }

        return new Adapter\Local(dirname($path),false);
    }
}
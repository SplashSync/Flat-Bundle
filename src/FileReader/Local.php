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
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Read Files from Local System using Gaufrette Local Adapter
 */
class Local implements FileReaderInterface
{
    private string $projectDir;

    /**
     * Service Cosntructor
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->projectDir = $kernel->getProjectDir();
    }

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
        $path = UrlAnalyser::getRealPath($url, $this->projectDir);
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
        if (!($path = UrlAnalyser::getRealPath($url, $this->projectDir))) {
            return null;
        }

        return (string) $adapter->read(basename($path)) ?: null;
    }

    //====================================================================//
    // Private Methods
    //====================================================================//

    /**
     * @param string $url
     *
     * @return null|Adapter
     */
    private function getAdapter(string $url): ?Adapter
    {
        $path = UrlAnalyser::getRealPath($url, $this->projectDir);

        if ($path && !$this->validate($url)) {
            return Splash::log()->errNull(sprintf("[%s] Read Fail: File not found", $url));
        }

        return new Adapter\Local(dirname((string) $path), false);
    }
}

<?php

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
     * @return string|null
     */
    public function read(string $url): ?string;
}
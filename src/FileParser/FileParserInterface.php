<?php

namespace Splash\Connectors\Flat\FileParser;

interface FileParserInterface
{
    /**
     * Get Parser Code
     *
     * @return string
     */
    public function code(): string;

    /**
     * Get Parser Name
     *
     * @return string
     */
    public function name(): string;

    /**
     * Check if this Parser Handle this kind of file
     *
     * @param string $extension
     *
     * @return bool
     */
    public function handle(string $extension): bool;

    /**
     * Parse Raw File Contents
     *
     * @param string $contents
     *
     * @return null|array<int, array<string, mixed>>
     */
    public function parse(string $contents): ?array;
}
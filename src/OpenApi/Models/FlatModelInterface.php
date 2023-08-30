<?php

namespace Splash\Connectors\Flat\OpenApi\Models;

interface FlatModelInterface
{
    /**
     * Get Model Identifier Field Key
     *
     * @return string
     */
    public static function getIdentifierKey(): string;

    /**
     * Get List of Filtered Fields Keys
     *
     * @return string[]
     */
    public static function getFilterKeys(): array;
}
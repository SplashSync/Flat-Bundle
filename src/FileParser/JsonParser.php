<?php

namespace Splash\Connectors\Flat\FileParser;

use Splash\Client\Splash;

/**
 * Parser for Json Files
 */
class JsonParser implements FileParserInterface
{

    /**
     * @inheritDoc
     */
    public function code(): string
    {
        return 'json';
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "Json Files Parser";
    }

    /**
     * @inheritDoc
     */
    public function handle(string $extension): bool
    {
        return in_array($extension, array("json", ".json"));
    }

    /**
     * @inheritDoc
     */
    public function parse(string $contents): ?array
    {
        try {
            return json_decode($contents, true,JSON_THROW_ON_ERROR );
        } catch (\Throwable $ex) {
            Splash::log()->report($ex);
        }

        return null;
    }
}
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

namespace Splash\Connectors\Flat\FileParser;

use Splash\Client\Splash;
use Throwable;

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
        return in_array($extension, array("json", ".json"), true);
    }

    /**
     * @inheritDoc
     */
    public function parse(string $contents): ?array
    {
        try {
            /** @phpstan-ignore-next-line  */
            return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $ex) {
            Splash::log()->report($ex);
        }

        return null;
    }
}

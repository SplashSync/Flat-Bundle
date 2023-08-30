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

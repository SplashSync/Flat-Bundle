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

namespace Splash\Connectors\Flat\Formater;

interface FormaterInterface
{
    /**
     * Get Formater Code
     *
     * @return string
     */
    public function code(): string;

    /**
     * Get Formater Name
     *
     * @return string
     */
    public function name(): string;

    /**
     * Format ONE data .
     *
     * @param array $object
     *
     * @return array
     */
    public function format(array $object): array;

    /**
     * Format MANY data.
     *
     * @param array $data
     *
     * @return array[]
     */
    public function formatMany(array $data): array;
}

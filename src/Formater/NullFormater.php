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

class NullFormater extends AbstractFormater
{
    const CODE = "null-formater";

    /**
     * @inheritDoc
     */
    public function code(): string
    {
        return self::CODE;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "Null Formater: Does nothing !";
    }

    /**
     * @inheritDoc
     */
    public function format(array $object): array
    {
        return $object;
    }

    /**
     * @inheritDoc
     */
    public function formatMany(array $data): array
    {
        return $data;
    }
}

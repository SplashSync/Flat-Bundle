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

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

class EmptyModel implements FlatModelInterface
{
    /**
     * Unique identifier.
     *
     * @var string
     *
     * @SerializedName("id")
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @Type("string")
     *
     * @Groups ({"Read", "List"})
     */
    public string $id;

    /**
     * @inheritDoc
     */
    public static function getIdentifierKey(): string
    {
        return "id";
    }

    /**
     * @inheritDoc
     */
    public static function getFilterKeys(): array
    {
        return array();
    }
}

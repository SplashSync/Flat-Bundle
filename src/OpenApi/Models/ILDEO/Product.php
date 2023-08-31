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

namespace Splash\Connectors\Flat\OpenApi\Models\ILDEO;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\PostDeserialize;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Splash\Connectors\Flat\OpenApi\Models\FlatModelInterface;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class Product implements FlatModelInterface
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
    public $id;

    /**
     * Model SKU.
     *
     * @var string
     *
     * @SerializedName("sku")
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @Type("string")
     *
     * @Groups ({"Read", "List"})
     */
    public string $sku;

    /**
     * Model EAN.
     *
     * @var null|int
     *
     * @SerializedName("ean")
     *
     * @Assert\Type("int")
     *
     * @Type("int")
     *
     * @Groups ({"Read", "List"})
     */
    public ?int $ean;

    /**
     * Name.
     *
     * @var string
     *
     * @SerializedName("name")
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @Type("string")
     *
     * @Groups ({"Read", "List"})
     *
     * @SPL\Description("Product Name")
     */
    public string $name;

    /**
     * Product Description.
     *
     * @var null|string
     *
     * @SerializedName("description")
     *
     * @Assert\Type("string")
     *
     * @Type("string")
     *
     * @Groups ({"Read"})
     *
     * @SPL\Description("Product Description")
     */
    public ?string $description = null;

    /**
     * Product Details.
     *
     * @var null|string
     *
     * @SerializedName("details")
     *
     * @Assert\Type("string")
     *
     * @Type("string")
     *
     * @Groups ({"Read"})
     *
     * @SPL\Description("Product Details")
     */
    public ?string $details = null;

    /**
     * Image 1.
     *
     * @var string
     *
     * @SerializedName("image1")
     *
     * @Assert\Type("string")
     *
     * @Type("string")
     *
     * @Groups ({"Read"})
     *
     * @SPL\Type("url")
     *
     * @SPL\Description("Image 1")
     */
    public string $image1;

    /**
     * @PostDeserialize
     *
     * @return void
     */
    public function postDeserialize(): void
    {
        $this->id = $this->sku;
    }

    /**
     * @inheritDoc
     */
    public static function getIdentifierKey(): string
    {
        return "sku";
    }

    /**
     * @inheritDoc
     */
    public static function getFilterKeys(): array
    {
        return array("sku", "name");
    }
}

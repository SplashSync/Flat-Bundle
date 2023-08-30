<?php

namespace Splash\Connectors\Flat\OpenApi\Models\Samples;

use DateTime;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\PostDeserialize;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Splash\Connectors\Flat\OpenApi\Models\FlatModelInterface;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class Basic implements FlatModelInterface
{
    /**
     * Unique identifier.
     *
     * @var string
     * @SerializedName("id")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "List"})
     */
    public $id;

    /**
     * Model SKU.
     *
     * @var string
     * @SerializedName("sku")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "List"})
     */
    public string $sku;

    /**
     * Label.
     *
     * @var string
     * @SerializedName("label")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read", "List"})
     * @SPL\Description("This is a Label")
     */
    public string $label;

    /**
     * Description.
     *
     * @var string
     * @SerializedName("description")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     * @Groups ({"Read"})
     * @SPL\Description("This is a Description")
     */
    public string $description;

    /**
     * Stock.
     *
     * @var int
     * @SerializedName("stock")
     * @Assert\NotNull()
     * @Assert\Type("int")
     * @Type("int")
     * @Groups ({"Read", "List"})
     * @SPL\Description("This is an Int")
     */
    public int $stock = 0;

    /**
     * Updated At.
     *
     * @var null|DateTime
     * @SerializedName("updated_at")
     * @Assert\Type("DateTime")
     * @Type("DateTime<'Y-m-d H:i:s'>")
     * @Groups ({"Read"})
     * @SPL\Description("This is a DateTime")
     */
    public ?DateTime $updated_at = null;

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
        return array("sku", "label");
    }
}
<?php

namespace Splash\Connectors\Flat\OpenApi\Models;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\PostDeserialize;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Splash\Connectors\Flat\OpenApi\Models\FlatModelInterface;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

class EmptyModel implements FlatModelInterface
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
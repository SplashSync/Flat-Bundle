<?php

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
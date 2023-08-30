<?php

namespace Splash\Connectors\Flat\Formater;

abstract class AbstractFormater implements FormaterInterface
{
    /**
     * @inheritDoc
     */
    public function formatMany(array $data): array
    {
        $objects = array();
        foreach ($data as $key => $item) {
            $objects[$key] = $this->format($item);
        }

        return $objects;
    }
}
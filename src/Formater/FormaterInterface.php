<?php

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
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

namespace Splash\Connectors\Flat\Services;

use Exception;
use Splash\Connectors\Flat\Formater\FormaterInterface;
use Splash\Connectors\Flat\Formater\NullFormater;

/**
 * Manage Access to File Download Adapters
 */
class DataFormater
{
    /**
     * @param FormaterInterface[] $taggedFormater
     */
    public function __construct(private iterable $taggedFormater)
    {
    }

    /**
     * Format ONE data
     *
     * @throws Exception
     */
    public function format(array $object, ?string $formater = null) : array
    {
        return $this->getFormater($formater)->format($object);
    }

    /**
     * Get Form Choices for Parsers
     */
    public function getChoices() : array
    {
        $choices = array();
        foreach ($this->taggedFormater as $formater) {
            $choices[$formater->name()] = $formater->code();
        }

        return $choices;
    }

    /**
     * Format MANY data
     *
     * @throws Exception
     */
    public function formatMany(array $object, ?string $formater = null) : array
    {
        return $this->getFormater($formater)->formatMany($object);
    }

    /**
     * Get Data Formater
     *
     * @throws Exception
     */
    private function getFormater(?string $code = null) : FormaterInterface
    {
        $code = $code ?? NullFormater::CODE;
        //====================================================================//
        // Find Formater
        foreach ($this->taggedFormater as $formater) {
            if ($formater->code() != $code) {
                continue;
            }

            return $formater;
        }

        throw new Exception(sprintf("Unable to find %s formater", $code));
    }
}

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

use Splash\Connectors\Flat\FileParser\FileParserInterface;
use Splash\Connectors\Flat\Helpers\UrlAnalyser;
use Splash\Core\SplashCore as Splash;

/**
 * Manage Access to File Parsers
 */
class FileParser
{
    /**
     * @param FileParserInterface[] $fileParsers
     */
    public function __construct(private iterable $fileParsers)
    {
    }

    /**
     * Get Form Choices for Parsers
     */
    public function getChoices() : array
    {
        $choices = array(
            "Auto" => "auto"
        );
        foreach ($this->fileParsers as $parser) {
            $choices[$parser->name()] = $parser->code();
        }

        return $choices;
    }

    /**
     * Validate Object Target Contents Parser
     */
    public function validate(string $objectName, mixed $url, mixed $code) : bool
    {
        //====================================================================//
        // Verify Target Url
        if (empty($url) || !is_string($url)) {
            return Splash::log()->err(sprintf("[%s] Remote file Url must be a string", $objectName));
        }
        //====================================================================//
        // Verify Parser Code
        if (empty($code) || !is_string($code)) {
            return Splash::log()->err(sprintf("[%s] Parser Code must be a string", $objectName));
        }
        //====================================================================//
        // Verify Parser Detection
        if (!$this->getParser($url, $code)) {
            return Splash::log()->err(sprintf("[%s] Parser %s not Found", $objectName, $code));
        }

        return true;
    }

    /**
     * Detect Parser for File
     */
    public function parse(string $url, ?string $code, ?string $contents): ?array
    {
        //====================================================================//
        // Safety Check
        if (!$contents) {
            return null;
        }
        //====================================================================//
        // Detect Parser
        if (!($parser = $this->getParser($url, $code))) {
            return null;
        }

        return $parser->parse($contents);
    }

    /**
     * Detect Parser for File
     */
    public function getParser(string $url, ?string $code): ?FileParserInterface
    {
        $code = $code ?? "auto" ?: "auto";
        $extension = UrlAnalyser::getExtension($url);
        //====================================================================//
        // Auto Mode >> First Parser Who handle this Extension
        if ("auto" == $code) {
            foreach ($this->fileParsers as $parser) {
                if ($parser->handle((string) $extension)) {
                    return $parser;
                }
            }
        } else {
            //====================================================================//
            // Manual >> Parser Selected by User
            foreach ($this->fileParsers as $parser) {
                if ($parser->code() == $code) {
                    return $parser;
                }
            }
        }

        return null;
    }
}

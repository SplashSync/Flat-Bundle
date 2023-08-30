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

use Splash\Bundle\Models\Connectors\ConfigurationAwareTrait;
use Splash\Client\Splash;
use Splash\Connectors\Flat\Collections\Collection;
use Splash\Connectors\Flat\OpenApi\Models\FlatModelInterface;
use Symfony\Component\Cache\Adapter\TraceableAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @property TraceableAdapter $appCache
 */
class DataCollector
{
    use ConfigurationAwareTrait;

    /**
     * Class Constructor
     */
    public function __construct(
        private FileReader    $fileReader,
        private FileParser    $fileParser,
        private DataFormater  $dataFormater,
        private CacheInterface $appCache
    ) {
    }

    //====================================================================//
    // VALIDATION
    //====================================================================//

    /**
     * Validate Object Targets
     */
    public function validateTargets(string $objectType, array $targets) : bool
    {
        //====================================================================//
        // Verify Targets List is Not Empty
        if (empty($targets)) {
            return Splash::log()->war(sprintf("[%s] Remote files list is empty", $objectType));
        }
        //====================================================================//
        // Verify Targets
        foreach ($targets as $targetUrl => $targetParser) {
            //====================================================================//
            // Verify Target Url
            if (!$this->fileReader->validate($objectType, $targetUrl)) {
                return false;
            }
            //====================================================================//
            // Verify Parser
            if (!$this->fileParser->validate($objectType, $targetUrl, $targetParser)) {
                return false;
            }
        }

        return true;
    }

    //====================================================================//
    // DATA DOWNLOAD & CACHING
    //====================================================================//

    /**
     * Get Cached Remote Data Collection
     */
    public function getCollection(bool $force = false): Collection
    {
        //====================================================================//
        // Get/Create Cache Item
        $cacheItem = $this->appCache->getItem($this->getCacheKey());
        $collection = $cacheItem->get();
        if (!$collection instanceof Collection) {
            $collection = new Collection();
        }
        /** @var string $ttl */
        $ttl = $this->getParameter("ttl", "-1 hour");
        //====================================================================//
        // Refresh Cache Item if Needed
        if ($collection->isOutdated($ttl) || $force) {
            //====================================================================//
            // Setup Item TTL
            $cacheItem->expiresAfter(null);
            //====================================================================//
            // Download Item Data
            /** @var array $targets */
            $targets = $this->getParameter("targets", array());
            /** @var null|string $formater */
            $formater = $this->getParameter("formater", null);
            $data = $this->download($targets, $formater);
            if ($data) {
                //====================================================================//
                // Import Raw Data on Collection
                $collection->import((string) $this->getModelIdentifier(), $data);
                //====================================================================//
                // Update Collection on Cache
                $cacheItem->set($collection);
                $this->appCache->save($cacheItem);
            }
        }

        return $collection;
    }

    /**
     * Refresh Remote Data Cache
     *
     * @return null|string
     */
    public function getModelIdentifier(): ?string
    {
        //====================================================================//
        //  Fetch & Check Data Model Class
        /** @var null|class-string $modelClass */
        $modelClass = $this->getParameter("model", null);
        if (!$modelClass || !is_subclass_of($modelClass, FlatModelInterface::class)) {
            return Splash::log()->errNull(sprintf(
                "Flat Model %s must implement %s",
                $modelClass,
                FlatModelInterface::class
            ));
        }

        //====================================================================//
        //  Get Data Model Identifier Field
        return $modelClass::getIdentifierKey();
    }

    /**
     * Refresh Remote Data Cache
     *
     * @return null|string[]
     */
    public function getModelFilterKeys(): ?array
    {
        //====================================================================//
        //  Fetch & Check Data Model Class
        /** @var null|class-string $modelClass */
        $modelClass = $this->getParameter("model", null);
        if (!$modelClass || !is_subclass_of($modelClass, FlatModelInterface::class)) {
            return Splash::log()->errNull(sprintf(
                "Flat Model %s must implement %s",
                $modelClass,
                FlatModelInterface::class
            ));
        }

        return $modelClass::getFilterKeys();
    }

    /**
     * Mark all Objects as Committed
     *
     * @param array $objectIds
     *
     * @return void
     */
    public function setCommitted(array $objectIds): void
    {
        //====================================================================//
        // Get Cache Item
        /** @var CacheItem $cacheItem */
        $cacheItem = $this->appCache->getItem($this->getCacheKey());
        $collection = $cacheItem->get();
        if (!$collection instanceof Collection) {
            return;
        }
        //====================================================================//
        // Mark Object IDs as Committed
        $collection->setCommitted($objectIds);
        //====================================================================//
        // Update Collection on Cache
        $cacheItem->set($collection);
        $this->appCache->save($cacheItem);
    }

    /**
     * Download RAW Remote Data
     */
    private function download(array $targets, ?string $formater): ?array
    {
        $contents = array();
        //====================================================================//
        //  Load Raw Data from Files
        foreach ($targets as $url => $parser) {
            //====================================================================//
            //  Load Raw File Contents
            $rawContents = $this->fileReader->read($url);
            if (!$rawContents) {
                continue;
            }
            //====================================================================//
            //  Parse File Contents
            $parsedContents = $this->fileParser->parse($url, $parser, $rawContents);
            if ($parsedContents) {
                $contents = array_replace_recursive($contents, $parsedContents);
            }
        }

        //====================================================================//
        //  Format Data using Data Formater
        if ($contents) {
            try {
                $contents = $this->dataFormater->formatMany($contents, $formater);
            } catch (\Exception $e) {
                Splash::log()->err($e->getMessage());
            }
        }

        return empty($contents) ? null : $contents;
    }

    /**
     * Get Cache Key for Current Object
     *
     * @return string
     */
    private function getCacheKey(): string
    {
        return "SplashFlatCollection".md5(serialize(array(
            $this->getSplashType(), $this->getWebserviceId()
        )));
    }
}

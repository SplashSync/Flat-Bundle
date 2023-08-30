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

namespace Splash\Connectors\Flat\OpenApi\Connexion;

use Splash\Bundle\Models\Connectors\ConfigurationAwareTrait;
use Splash\Connectors\Flat\Collections\CollectionItem;
use Splash\Connectors\Flat\Services\DataCollector;

/**
 * OpenApi Connexion for Flat Files
 */
class FlatConnexion extends NullConnexion
{
    use ConfigurationAwareTrait;

    const LIST = "data-list";

    const READ = "data-read";

    /**
     * Connexion Constructor
     *
     * @param DataCollector $dataCollector
     */
    public function __construct(private DataCollector $dataCollector)
    {
    }

    /**
     * @inheritDoc
     */
    public function get(?string $path, array $data = null): ?array
    {
        //====================================================================//
        // Configure Data Collector
        $this->dataCollector->configure($this->getSplashType(), $this->getWebserviceId(), $this->getConfiguration());
        //====================================================================//
        // Get Objects List
        if (str_contains((string) $path, self::LIST)) {
            //====================================================================//
            // Get Filtered Collection
            $collection = $this->dataCollector
                ->getCollection()
                ->filterByModelKeys(
                    $data["filter"] ?? null,
                    $this->dataCollector->getModelFilterKeys() ?? array()
                )
            ;
            //====================================================================//
            // Get Response
            return array(
                "total" => $collection->count(),
                "_embedded" => array(
                    "items" => $collection
                        ->paginate((int) ($data["limit"] ?? 25), (int) ($data["page"] ?? 1))
                        ->getData()
                ),
            );
        }
        //====================================================================//
        // Get Objects Data
        if (str_contains((string) $path, self::READ)) {
            //====================================================================//
            // Extract Objects ID
            $objectId = null;
            sscanf((string) $path, self::READ."/%s", $objectId);
            if ($objectId) {
                $objectItem = $this->dataCollector
                    ->getCollection()
                    ->get((string) $objectId)
                ;

                return ($objectItem instanceof CollectionItem) ? $objectItem->toArray() : null;
            }
        }

        return null;
    }

    /**
     * Refresh Data from remote Servers
     */
    public function refresh(): void
    {
        //====================================================================//
        // Configure Data Collector
        $this->dataCollector->configure($this->getSplashType(), $this->getWebserviceId(), $this->getConfiguration());
        //====================================================================//
        // Get Collection (FORCED)
        $this->dataCollector->getCollection(true);
    }

    /**
     * Fetch List of Updated Objects Ids
     *
     * @return array
     */
    public function getUpdatedIds(): array
    {
        //====================================================================//
        // Configure Data Collector
        $this->dataCollector->configure($this->getSplashType(), $this->getWebserviceId(), $this->getConfiguration());
        //====================================================================//
        // Get Object IDs
        $objectIds = array_replace(
            $this->dataCollector->getCollection()->getCreated()->getKeys(),
            $this->dataCollector->getCollection()->getUpdated()->getKeys(),
        );
        //====================================================================//
        // Mark Object IDs as Committed
        $this->dataCollector->setCommitted($objectIds);

        return $objectIds;
    }

    /**
     * Fetch List of Updated Objects Ids
     *
     * @return array
     */
    public function getDeletedIds(): array
    {
        //====================================================================//
        // Configure Data Collector
        $this->dataCollector->configure($this->getSplashType(), $this->getWebserviceId(), $this->getConfiguration());
        //====================================================================//
        // Get Object IDs
        $objectIds = $this->dataCollector->getCollection()->getDeleted()->getKeys();
        //====================================================================//
        // Mark Object IDs as Committed
        $this->dataCollector->setCommitted($objectIds);

        return $objectIds;
    }
}

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

namespace Splash\Connectors\Flat\Collections;

use DateTime;

class CollectionItem
{
    /**
     * Object Data Storage
     */
    private array $data;

    /**
     * Last Imported Data MD5
     */
    private ?string $md5;

    /**
     * Date Object was Created in Collection
     */
    private ?DateTime $created;

    /**
     * Date Object was Updated in Collection
     */
    private ?DateTime $updated;

    /**
     * Date Object was Deleted in Collection
     */
    private ?DateTime $deleted;

    /**
     * Date Object was Imported
     */
    private ?DateTime $imported;

    /**
     * Date Object was Committed
     */
    private ?DateTime $committed;

    /**
     * Create a New Data Item
     */
    public function __construct(DateTime $date, array $rawData)
    {
        $this->created = $this->imported = $date;
        $this->updated = $this->deleted = $this->committed = null;
        $this->data = $rawData;
        $this->md5 = md5(serialize($rawData));
    }

    /**
     * Update Data Item
     */
    public function update(DateTime $date, array $rawData): bool
    {
        $md5 = md5(serialize($rawData));
        $this->imported = $date;

        if ($this->md5 != $md5) {
            $this->data = $rawData;
            $this->md5 = $md5;
            $this->created = $this->deleted = $this->committed = null;
            $this->updated = $date;
        }

        return false;
    }

    /**
     * Mark Object as Committed
     */
    public function setCommitted(DateTime $date): void
    {
        $this->committed = $date;
    }

    /**
     * Get Raw Data from Item
     */
    public function toArray(): array
    {
        return $this->data;
    }

    //====================================================================//
    // DELETE CHECK
    //====================================================================//

    /**
     * Check if Object needs to be Deleted
     */
    public function checkDeleted(DateTime $importDate): bool
    {
        //====================================================================//
        // Object Never Imported
        if (!$this->imported) {
            return false;
        }
        //====================================================================//
        // Object NOT Imported on last Refresh
        if ($this->imported < $importDate) {
            $this->deleted = $importDate;
            $this->created = $this->updated = $this->committed = null;

            return true;
        }

        return false;
    }

    //====================================================================//
    // STATUS CHECKERS
    //====================================================================//

    /**
     * Data Item was Created but not Committed
     */
    public function isCommitCreated(): bool
    {
        return empty($this->committed) && !empty($this->created);
    }

    /**
     * Data Item was Updated but not Committed
     */
    public function isCommitUpdated(): bool
    {
        return empty($this->committed) && !empty($this->updated);
    }

    /**
     * Data Item was Deleted but not Committed
     */
    public function isCommitDeleted(): bool
    {
        return empty($this->committed) && $this->isDeleted();
    }

    /**
     * Data Item was Deleted but not Committed
     */
    public function isDeleted(): bool
    {
        return !empty($this->deleted);
    }

    /**
     * Data string $filter, array $filterKeys
     *
     * @param string   $filter
     * @param string[] $filterKeys
     *
     * @return bool
     */
    public function isSearched(string $filter, array $filterKeys): bool
    {
        if (empty($filterKeys)) {
            return in_array($filter, $this->data, false);
        }

        return in_array(
            $filter,
            array_intersect_key($this->data, $filterKeys),
            false
        );
    }
}

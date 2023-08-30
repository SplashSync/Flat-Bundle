<?php

namespace Splash\Connectors\Flat\Collections;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

/**
 * @psalm-type T
 */
class Collection extends ArrayCollection
{
    /**
     * Date of Last Update
     *
     * @var DateTime|null
     */
    private ?DateTime $updated = null;

    /**
     * Import RAW Data from Remote Files
     */
    public function import(string $identifier, array $rawData): void
    {
        $date = new \DateTime();
        //====================================================================//
        // IMPORT => Walk on Raw Data
        foreach ($rawData as $rawItem) {
            //====================================================================//
            // Validate & Extract Identifier
            if (!$itemId = $this->validateItem($identifier, $rawItem)) {
                continue;
            }
            /** @var array $rawItem */
            //====================================================================//
            // Import or Create Collection Item
            if ($item = $this->get($itemId)) {
                $item->update($date, $rawItem);
            } else {
                $item = new CollectionItem($date, $rawItem);
            }
            $this->set($itemId, $item);
        }
        //====================================================================//
        // DELETE => Detect Deleted Objects
        $iterator = $this->getIterator();
        $iterator->rewind();
        /** @var CollectionItem $item */
        while ($item = $iterator->current()) {
            $item->checkDeleted($date);
            $iterator->next();
        }

        //====================================================================//
        // Mark Collection as Updated
        $this->updated = $date;
    }

    /**
     * Mark all Objects as Committed
     *
     * @param string[] $objectIds
     *
     * @return void
     */
    public function setCommitted(array $objectIds): void
    {
        $date = new \DateTime();
        //====================================================================//
        // IMPORT => Walk on Raw Data
        foreach ($objectIds as $objectId) {
            //====================================================================//
            // Get Object from Collection
            /** @var CollectionItem|null $collectionItem */
            if (!$collectionItem = $this->get($objectId)) {
                continue;
            }
            //====================================================================//
            // Object was Deleted
            if ($collectionItem->isDeleted()) {
                $this->remove($objectId);

                continue;
            }
            //====================================================================//
            // Object was Created or Updated
            $collectionItem->setCommitted($date);
        }
    }

    /**
     * Filter Collection
     */
    public function filterByModelKeys(?string $filter, array $filterKeys): Collection
    {
        if (empty($filter)) {
            return $this;
        }
        $filterKeys = array_flip($filterKeys);

        return $this->filter(function(CollectionItem $item) use ($filter, $filterKeys) {
            return $item->isSearched($filter, $filterKeys);
        });


        return $this;
    }

    /**
     * Paginate Collection
     */
    public function paginate(int $limit = 25, int $page = 1): Collection
    {
        $start = $limit * abs($page - 1);

        return new self($this->slice($start, $limit));
    }

    /**
     * @return array[]     */
    public function getData(): array
    {
        return array_map(function(CollectionItem $item) {
            return $item->toArray();
        }, $this->toArray());
    }

    /**
     * Get List of New Objects to Commit
     *
     * @return Collection<CollectionItem>
     */
    public function getCreated(): Collection
    {
        return $this->filter(function(CollectionItem $item) {
            return $item->isCommitCreated();
        });
    }

    /**
     * Get List of Updated Objects to Commit
     *
     * @return Collection<CollectionItem>
     */
    public function getUpdated(): Collection
    {
        return $this->filter(function(CollectionItem $item) {
            return $item->isCommitUpdated();
        });
    }

    /**
     * Get List of Deleted Objects to Commit
     *
     * @return Collection<CollectionItem>
     */
    public function getDeleted(): Collection
    {
        return $this->filter(function(CollectionItem $item) {
            return $item->isCommitDeleted();
        });
    }

    /**
     * Check if Collection needs to be refreshed
     *
     * @param string $ttl TTL String
     *
     * @return bool
     */
    public function isOutdated(string $ttl): bool
    {
        //====================================================================//
        // Collection Never Updated
        if (!$this->updated) {
            return true;
        }
        //====================================================================//
        // Collection Needs Update
        try {
            $maxDate = new DateTime($ttl);
        } catch (Exception $e) {
            return false;
        }

        return ($this->updated < $maxDate);
    }

    //====================================================================//
    // PRIVATE & LOW LEVEL METHODS
    //====================================================================//

    /**
     * Validate Item & Retrieve Object Identifier
     */
    private function validateItem(string $identifier, mixed $rawItem): ?string
    {
        //====================================================================//
        // Safety Check
        if (!is_array($rawItem)) {
            return null;
        }
        //====================================================================//
        // Extract Identifier
        if (!$identifier = ($rawItem[$identifier] ?? null)) {
            return null;
        }

        return $identifier;
    }
}
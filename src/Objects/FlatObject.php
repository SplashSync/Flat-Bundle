<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Flat\Objects;

use Exception;
use Splash\Bundle\Interfaces\Objects\TrackingInterface;
use Splash\Client\Splash;
use Splash\Connectors\Flat\Connector\FlatConnector;
use Splash\Connectors\Flat\OpenApi\Connexion\FlatConnexion;
use Splash\OpenApi\Action\JsonHal;
use Splash\OpenApi\Models\Objects\AbstractApiObject;
use Splash\OpenApi\Visitor\JsonHalVisitor;

/**
 * Generic Flat File Object
 */
class FlatObject extends AbstractApiObject implements TrackingInterface
{
    //====================================================================//
    // Object Definition Parameters
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    protected static bool $disabled = false;

    /**
     * {@inheritdoc}
     */
    protected static string $name = "FlatFile";

    /**
     * {@inheritdoc}
     */
    protected static string $description = "Generic Flat File";

    /**
     * {@inheritdoc}
     */
    protected static string $ico = "fa fa-product-hunt";

    //====================================================================//
    // Object Synchronization Recommended Configuration
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    protected static bool $allowPushCreated = false;

    /**
     * {@inheritdoc}
     */
    protected static bool $allowPushUpdated = false;

    /**
     * {@inheritdoc}
     */
    protected static bool $allowPushDeleted = false;

    //====================================================================//
    // General Class Variables
    //====================================================================//

    /**
     * @var FlatConnector
     */
    protected FlatConnector $connector;

    /**
     * Object Data Model
     *
     * @var string
     */
    private string $model;

    /**
     * Class Constructor
     *
     * @param FlatConnector $parentConnector
     * @param string $model
     *
     * @throws Exception
     */
    public function __construct(
        protected FlatConnector $parentConnector,
        string $model,
    ) {
        $this->connector = $parentConnector;
        $this->model = $model;
        //====================================================================//
        // Connector SelfTest
        $parentConnector->selfTest();
        //====================================================================//
        //  Load Translation File
        Splash::translator()->load('local');
        //====================================================================//
        // Parent Construct
        parent::__construct(
            $parentConnector->getConnexion(),
            $parentConnector->getHydrator(),
            $model,
            $this->getVisitor()
        );
    }


    public function configure(string $type, string $webserviceId, array $configuration): self
    {
        self::$name = ucwords($type);
        self::$description = sprintf("Flat %s File", $type);

        $this->visitor->getConnexion()->configure($type, $webserviceId, $configuration);

        $this->visitor->setModel(
            $this->visitor->getModel(),
            FlatConnexion::LIST,
            FlatConnexion::READ."/{id}"
        );

        return parent::configure($type, $webserviceId, $configuration);
    }

    /**
     * Refresh Data from remote Servers
     *
     * @throws Exception
     */
    public function refresh(): void
    {
        $connexion = $this->getVisitor()->getConnexion();
        if (!$connexion instanceof FlatConnexion) {
            return;
        }

        $connexion->refresh();
    }

    /**
     * Get Open API Visitor
     *
     * @throws Exception
     */
    public function getVisitor(): JsonHalVisitor
    {
        if (!isset($this->visitor)) {
            $this->visitor = new JsonHalVisitor(
                $this->connector->getConnexion(),
                $this->connector->getHydrator(),
                $this->model
            );
            $this->visitor->setModel(
                $this->visitor->getModel(),
                FlatConnexion::LIST,
                FlatConnexion::READ."/{id}"
            );
            $this->visitor->setListAction(
                JsonHal\ListAction::class,
                array("filterKey" => "filter")
            );
        }

        return $this->visitor;
    }

    /**
     * @inheritDoc
     */
    public function getTrackingDelay(): int
    {
        return 1;
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedIds(): array
    {

        try {
            $connexion = $this->getVisitor()->getConnexion();
        } catch (Exception $e) {
            return array();
        }
        if (!$connexion instanceof FlatConnexion) {
            return array();
        }

        return $connexion->getUpdatedIds();
    }

    /**
     * @inheritDoc
     */
    public function getDeletedIds(): array
    {
        try {
            $connexion = $this->getVisitor()->getConnexion();
        } catch (Exception $e) {
            return array();
        }
        if (!$connexion instanceof FlatConnexion) {
            return array();
        }

        return $connexion->getDeletedIds();
    }
}

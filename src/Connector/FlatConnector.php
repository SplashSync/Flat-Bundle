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

namespace Splash\Connectors\Flat\Connector;

use ArrayObject;
use Exception;
use Psr\Log\LoggerInterface;
use Splash\Bundle\Interfaces\Connectors\TrackingInterface;
use Splash\Bundle\Models\AbstractConnector;
use Splash\Bundle\Models\Connectors;
use Splash\Connectors\Flat\Controller\RefreshAction;
use Splash\Connectors\Flat\Form\EditFormType;
use Splash\Connectors\Flat\Objects\FlatObject;
use Splash\Connectors\Flat\OpenApi\Connexion\FlatConnexion;
use Splash\Connectors\Flat\OpenApi\Models;
use Splash\Connectors\Flat\Services\DataCollector;
use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Hydrator\Hydrator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Flat Files Connector for Splash
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FlatConnector extends AbstractConnector implements TrackingInterface
{
    use Connectors\GenericObjectMapperTrait;
    use Connectors\GenericWidgetMapperTrait;

    /**
     * Widgets Type Class Map
     *
     * @var array
     */
    protected static array $widgetsMap = array(
        "SelfTest" => "Splash\\Connectors\\Flat\\Widgets\\SelfTest",
    );

    /**
     * Object Hydrator
     *
     * @var Hydrator
     */
    private Hydrator $hydrator;

    /**
     * @var string
     */
    private string $metaDir;

    /**
     * Splash Connector Constructor
     */
    public function __construct(
        private DataCollector    $dataCollector,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface          $logger
    ) {
        parent::__construct($eventDispatcher, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function ping() : bool
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function connect() : bool
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }
        //====================================================================//
        // Walk on defined Objects
        foreach ($this->getAvailableObjects() as $objectType) {
            //====================================================================//
            // Connect to Object
            try {
                $this->getObjectLocalClass($objectType);
            } catch (Exception $e) {
                Splash::log()->err($e->getMessage());
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function informations(ArrayObject  $informations) : ArrayObject
    {
        //====================================================================//
        // Server General Description
        $informations->shortdesc = "Flat Files Connector";
        $informations->longdesc = "Flat Files Connector for Splash";
        //====================================================================//
        // Company Informations
        $informations->company = "SplashSync";
        $informations->address = "--";
        $informations->zip = "33000";
        $informations->town = "Bordeaux";
        $informations->country = "France";
        $informations->www = "https://www.splashsync.com";
        $informations->email = "contact@splashsync.com";
        $informations->phone = "01.02.03.04.05";
        //====================================================================//
        // Server Logo & Ico
        $informations->icoraw = Splash::file()->readFileContents(
            dirname(__FILE__, 2)."/Resources/public/img/Flat-Icon.ico"
        );
        $informations->logourl = null;
        $informations->logoraw = Splash::file()->readFileContents(
            dirname(__FILE__, 2)."/Resources/public/img/Flat-Logo.png"
        );
        //====================================================================//
        // Server Informations
        $informations->servertype = "Flat";
        $informations->serverurl = "www.splashsync.com";
        //====================================================================//
        // Module Informations
        $informations->moduleauthor = "Splash Official <www.splashsync.com>";
        $informations->moduleversion = "master";

        return $informations;
    }

    /**
     * {@inheritdoc}
     */
    public function selfTest() : bool
    {
        $config = $this->getConfiguration();
        //====================================================================//
        // Walk On Configured Objects
        //====================================================================//
        foreach ($config["Objects"] ?? array() as $objectName => $objectConfig) {
            //====================================================================//
            // Verify Object Name
            if (empty($objectName) || !is_string($objectName)) {
                return Splash::log()->err("Object name must be defined!");
            }
            //====================================================================//
            // Verify Targets
            if (!$this->dataCollector->validateTargets($objectName, $objectConfig['targets'] ?? array())) {
                return false;
            }
        }

        return true;
    }

    //====================================================================//
    // Files Interfaces
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    public function getFile(string $filePath, string $fileMd5): ?array
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return null;
        }
        //====================================================================//
        // Read File Contents via Simple Get Request
        $rawResponse = file_get_contents($filePath);
        if (!$rawResponse || (md5($rawResponse) != $fileMd5)) {
            return null;
        }
        //====================================================================//
        // Build File Array
        $file = array();
        $file["name"] = $file["filename"] = pathinfo($filePath, PATHINFO_BASENAME);
        $file["path"] = $filePath;
        $file["url"] = null;
        $file["raw"] = base64_encode((string) $rawResponse);
        $file["md5"] = md5($rawResponse);
        $file["size"] = strlen($rawResponse);

        return $file;
    }

    //====================================================================//
    // Profile Interfaces
    //====================================================================//

    /**
     * Get Connector Profile Information
     *
     * @return array
     */
    public function getProfile() : array
    {
        return array(
            'enabled' => true,                                      // is Connector Enabled
            'beta' => false,                                        // is this a Beta release
            'type' => self::TYPE_HIDDEN,                            // Connector Type or Mode
            'name' => 'flat',                                       // Connector code (lowercase, no space allowed)
            'connector' => 'splash.connectors.flat',                // Connector Symfony Service
            'title' => 'profile.card.title',                        // Public short name
            'label' => 'profile.card.label',                        // Public long name
            'domain' => 'FlatBundle',                               // Translation domain for names
            'ico' => '/bundles/flat/img/Flat-Logo.png',             // Public Icon path
            'www' => 'https://www.splashsync.com',                  // Website Url
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectedTemplate() : string
    {
        return "@Flat/Profile/connected.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getOfflineTemplate() : string
    {
        return "@Flat/Profile/offline.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getNewTemplate() : string
    {
        return "@Flat/Profile/new.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilderName() : string
    {
        $this->selfTest();

        return EditFormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getMasterAction(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicActions() : array
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getSecuredActions() : array
    {
        return array(
            "refresh" => RefreshAction::class
        );
    }

    //====================================================================//
    //  CONNECTOR FEATURES
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    public function getAvailableObjects() : array
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return array();
        }

        //====================================================================//
        // Get Generic Object Types List
        /** @phpstan-ignore-next-line  */
        return array_keys($this->getConfiguration()["Objects"] ?? array());
    }

    /**
     * Refresh Object Remote Data Cache
     *
     * @param string $objectType
     *
     * @return bool
     */
    public function refreshObjectCache(string $objectType) : bool
    {
        try {
            $splObject = $this->getObjectLocalClass($objectType);

            $splObject->refresh();

            return Splash::log()->msg(
                sprintf("[%s] Refresh Done !", $objectType)
            );
        } catch (Exception $e) {
            return Splash::log()->err(
                sprintf("[%s] Refresh Fail %s", $objectType, $e->getMessage())
            );
        }
    }

    //====================================================================//
    // Open API Connector Interfaces
    //====================================================================//

    /**
     * Get Connector Api Connexion
     *
     * @throws Exception
     *
     * @return FlatConnexion
     */
    public function getConnexion() : FlatConnexion
    {
        return new FlatConnexion($this->dataCollector);
    }

    /**
     * Setup Cache Dir for Metadata
     */
    public function setMetaDir(string $metaDir) : void
    {
        $this->metaDir = $metaDir."/metadata/flat";
    }

    /**
     * @return Hydrator
     */
    public function getHydrator(): Hydrator
    {
        //====================================================================//
        // Configure Object Hydrator
        if (!isset($this->hydrator)) {
            $this->hydrator = new Hydrator($this->metaDir);
        }

        return $this->hydrator;
    }

    //====================================================================//
    //  OBJECTS FEATURES
    //====================================================================//

    /**
     * Return Configuration for Requested Object Type
     *
     * @throws Exception
     */
    private function getObjectConfiguration(string $objectType) : array
    {
        return $this->getConfiguration()["Objects"][$objectType] ?? array();
    }

    /**
     * Return a New Instance of Requested Object Type Class
     *
     * @throws Exception
     */
    private function getObjectLocalClass(string $objectType) : FlatObject
    {
        //====================================================================//
        // Safety Check => Validate Object Class
        if (null !== $this->isValidObjectClass(FlatObject::class)) {
            throw new Exception($this->isValidObjectClass(FlatObject::class));
        }
        //====================================================================//
        // Safety Check => Validate Model Class
        $modelClass = $this->getObjectConfiguration($objectType)['model'] ?? "Undefined";
        if (!is_string($modelClass) || !class_exists($modelClass)) {
            Splash::log()->err(sprintf("Model Class %s does not exists", $modelClass));
            $modelClass = Models\EmptyModel::class;
        }
        //====================================================================//
        // Create Object Class
        $genericObject = new FlatObject($this, $modelClass);
        //====================================================================//
        // Configure Flat Object !
        $genericObject->configure(
            $objectType,
            $this->getWebserviceId(),
            $this->getObjectConfiguration($objectType)
        );

        return $genericObject;
    }
}

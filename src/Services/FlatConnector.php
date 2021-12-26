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

namespace Splash\Connectors\Flat\Services;

use ArrayObject;
use Splash\Bundle\Interfaces\Connectors\TrackingInterface;
use Splash\Bundle\Models\AbstractConnector;
use Splash\Connectors\Flat\Form\EditFormType;
use Splash\Connectors\Optilog\Models\RestHelper as API;
use Splash\Connectors\Optilog\Models\StatusHelper;
use Splash\Core\SplashCore as Splash;

/**
 * Flat Files Connector for Splash
 */
class FlatConnector extends AbstractConnector implements TrackingInterface
{
    use \Splash\Bundle\Models\Connectors\GenericObjectMapperTrait;
    use \Splash\Bundle\Models\Connectors\GenericWidgetMapperTrait;

    /**
     * Objects Type Class Map
     *
     * @var array
     */
    protected static $objectsMap = array(
//        "Product" => "Splash\\Connectors\\Optilog\\Flat\\Product",
    );

    /**
     * Widgets Type Class Map
     *
     * @var array
     */
    protected static $widgetsMap = array(
//        "SelfTest" => "Splash\\Connectors\\Optilog\\Widgets\\SelfTest",
    );

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
        //====================================================================//
        // Perform Ping Test
//        return API::ping();

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
        // Perform Connect Test
//        if (!API::connect()) {
//            return false;
//        }

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
        $informations->address = "";
        $informations->zip = "33000";
        $informations->town = "Bordeaux";
        $informations->country = "France";
        $informations->www = "https://www.splashsync.com";
        $informations->email = "contact@splashsync.com";
        $informations->phone = "01.02.03.04.05";
        //====================================================================//
        // Server Logo & Ico
        $informations->icoraw = Splash::file()->readFileContents(
            dirname(dirname(__FILE__))."/Resources/public/img/Flat-Icon.ico"
        );
        $informations->logourl = null;
        $informations->logoraw = Splash::file()->readFileContents(
            dirname(dirname(__FILE__))."/Resources/public/img/Flat-Logo.png"
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
        return True;

//        $config = $this->getConfiguration();
//        //====================================================================//
//        // Verify Webservice Url is Set
//        //====================================================================//
//        if (!isset($config["WsHost"]) || !in_array($config["WsHost"], API::ENDPOINTS, true)) {
//            Splash::log()->err("Webservice Host is Invalid");
//
//            return false;
//        }
//        //====================================================================//
//        // Verify Api Key is Set
//        //====================================================================//
//        if (!isset($config["ApiKey"]) || empty($config["ApiKey"])) {
//            Splash::log()->err("Api Key is Invalid");
//
//            return false;
//        }
//        //====================================================================//
//        // Verify Api User is Set
//        //====================================================================//
//        if (empty($config["ApiUser"])) {
//            Splash::log()->err("Api User is Invalid");
//
//            return false;
//        }
//        //====================================================================//
//        // Verify Api Password is Set
//        //====================================================================//
//        if (empty($config["ApiPwd"])) {
//            Splash::log()->err("Api Pwd is Invalid");
//
//            return false;
//        }
//        //====================================================================//
//        // Configure Order Status Helper
//        StatusHelper::init($this->getParameter("useExtendedStatus", false));
//        //====================================================================//
//        // Configure Rest API
//        return API::configure(
//            $config["WsHost"],
//            $config["ApiKey"],
//            $config["ApiUser"],
//            $config["ApiPwd"]
//        );
    }

    //====================================================================//
    // Files Interfaces
    //====================================================================//

    /**
     * {@inheritdoc}
     */
    public function getFile(string $filePath, string $fileMd5)
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }

        Splash::log()->err("There are No Files Reading for Flat Up To Now!");

        return false;
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
    public function getMasterAction()
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
        );
    }

    //====================================================================//
    //  DEBUG FEATURES
    //====================================================================//

    /**
     * Check If Server is In Debug Mode
     *
     * @return bool
     */
    public function isDebugMode() : bool
    {
        return true;
    }
}

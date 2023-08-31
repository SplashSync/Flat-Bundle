<?php

return array(

    //==============================================================================
    // SYMFONY CORE
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => array("all" => true),
    Symfony\Bundle\MonologBundle\MonologBundle::class => array("all" => true),

    //==============================================================================
    // SYMFONY DEV & DEBUG BUNDLES
    Symfony\Bundle\DebugBundle\DebugBundle::class => array("dev" => true),

    //==============================================================================
    // SPLASH BUNDLES
    Splash\Bundle\SplashBundle::class => array("all" => true),
    Splash\Console\ConsoleBundle::class => array("all" => true),
    Splash\Connectors\Flat\FlatBundle::class => array("all" => true),
);
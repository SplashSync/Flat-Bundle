<?php

namespace Splash\Connectors\Flat\Controller;

use Splash\Bundle\Models\AbstractConnector;
use Splash\Bundle\Models\Local\ActionsTrait;
use Splash\Client\Splash;
use Splash\Connectors\Flat\Connector\FlatConnector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Refresh File Cache from Remote Servers
 */
class RefreshAction extends AbstractController
{
    use ActionsTrait;

    public function __invoke(AbstractConnector $connector)
    {
        if (!$connector instanceof FlatConnector) {
            return self::getDefaultResponse();
        }

        foreach ($connector->getAvailableObjects() as $objectType) {
            $connector->refreshObjectCache($objectType);
        }

        return new Response(Splash::log()->getHtmlLog());
    }
}
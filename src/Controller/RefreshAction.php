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

    public function __invoke(AbstractConnector $connector): Response
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

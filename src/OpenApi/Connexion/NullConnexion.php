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

use Httpful\Request;
use Httpful\Response;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

class NullConnexion implements ConnexionInterface
{
    const MSG = "This is NULL Connexion!! Should not be used...";

    /**
     * @inheritDoc
     */
    public function get(?string $path, array $data = null): ?array
    {
        throw new \Exception(self::MSG);
    }

    /**
     * @inheritDoc
     */
    public function getRaw(?string $path, array $data = null, bool $absoluteUrl = false): ?string
    {
        throw new \Exception(self::MSG);
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, array $data): ?array
    {
        throw new \Exception(self::MSG);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, array $data): ?array
    {
        throw new \Exception(self::MSG);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $path, array $data = null): ?array
    {
        throw new \Exception(self::MSG);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): ?array
    {
        throw new \Exception(self::MSG);
    }

    /**
     * @inheritDoc
     */
    public function getEndPoint(): string
    {
        throw new \Exception(self::MSG);
    }

    /**
     * @inheritDoc
     */
    public function getTemplate(): Request
    {
        throw new \Exception(self::MSG);
    }

    /** .
     * @inheritDoc
     */
    public function getLastResponse(): ?Response
    {
        return null;
    }
}

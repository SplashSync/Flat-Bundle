<?php

namespace Splash\Connectors\Flat\FileReader;

use Gaufrette\Adapter;
use Splash\Client\Splash;
use Splash\Connectors\Flat\Helpers\UrlAnalyser;

/**
 * Read Files from FTP servers using Gaufrette FTP Adapter
 */
class Sftp extends Ftp
{
    //====================================================================//
    // File Reader Implementation
    //====================================================================//

    /**
     * @inheritDoc
     */
    public function code(): string
    {
        return "sftp";
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return "SFTP files reader";
    }

    //====================================================================//
    // Private Methods
    //====================================================================//

    /**
     * @param string $url
     *
     * @return Adapter|null
     */
    protected function getAdapter(string $url): ?Adapter
    {
        if (!$this->validate($url)) {
            return Splash::log()->errNull(sprintf("[%s] SFTP Url is invalid", $url));
        }
        //====================================================================//
        // Create Library
        $sftp = new \phpseclib\Net\SFTP(UrlAnalyser::getHost($url), UrlAnalyser::getPort($url) ?: 22);
        //====================================================================//
        // Login manually with the Lib
        $sftp->login(UrlAnalyser::getUser($url), UrlAnalyser::getPass($url));

        return new Adapter\PhpseclibSftp($sftp);
    }
}
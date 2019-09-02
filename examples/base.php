<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';

use Guzzle\Http\Client;

class base
{
    private $_client;

    public function getConfig($alias)
    {
        return Config::getConfig($alias);
    }

    public function getRequestClient()
    {
        if (!$this->_client) {
            $this->_client = new Client();
        }

        return $this->_client;
    }

    public function buildRequestUrl($endpoint, $bucket, $filename = '')
    {
        if (preg_match('/^https?:\/\//', $endpoint)) {
            $endpoint = str_replace('://', "://{$bucket}.", $endpoint);
        } else {
            $endpoint = "https://{$bucket}" . $endpoint;
        }
        $url = $endpoint . ($filename ? "/{$filename}" : '');

        return $url;
    }

    public function buildHost($requestUrl)
    {
        return str_replace(array('https', 'http'), '', $requestUrl);
    }

    public function getDate()
    {
        return gmdate('D, d M Y H:i:s \G\M\T');
    }


    public function getMimeType($object, $file = null)
    {
        return 'binary/octet-stream';
    }
}

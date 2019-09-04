<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config.php';

use Guzzle\Http\Client;
use \Config as BaseConfig;
use League\Flysystem\Config;

class base
{
    private $_client;

    /**
     * 获取配置信息
     *
     * @author: w00445976<wangtao178@huawei.com>
     * @date 2019-09-03 11:26:57
     *
     * @param $alias
     *
     * @return Config
     */
    public function getConfig($alias)
    {
        return new Config(BaseConfig::getConfig($alias));
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
            $endpoint = "https://{$bucket}." . $endpoint;
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

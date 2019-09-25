<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss\Drivers;

use Guzzle\Http\Client;
use Namet\Oss\Config;
use Namet\Oss\Interfaces\BucketInterface;
use Namet\Oss\Interfaces\FileInterface;


abstract class Base implements FileInterface, BucketInterface
{
    /**
     * @var \Namet\Oss\Config|array
     */ 
    protected $config = array();

    /**
     * @var Guzzle\Http\Client
     */ 
    private $_client = null;

    /**
     * Constructor function 
     *
     * @param array $config 配置信息
     */   
    public function __construct($config = array())
    {
        if ($config) {
            $this->config = new Config($config);
        }
    }

    /**
     * 获取GMT格式的时间
     *
     * @return string 
     */ 
    protected function getDate()
    {
        return gmdate('D, d M Y H:i:s \G\M\T');
    }

    /**
     * 获取HTTP客户端
     *
     * @return Guzzle\Http\Client
     */ 
    protected function getClient()
    {
        if (!$this->_client) {
            $this->_client = new Client();
        }

        return $this->_client;
    }

    /**
     * 获取文件的mime type
     * TODO to be completed
     * @return string
     */ 
    protected function getMimeType()
    {
        return 'binary/octet-stream';
    }


    protected function getRequestMethod($method)
    {
        $map = [
            'putObject' => 'PUT',
            ''
        ];
    }
}


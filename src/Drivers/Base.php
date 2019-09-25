<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss\Drivers;

use Guzzle\Http\Client;
use Namet\Oss\Config;
use Namet\Oss\OssException;

abstract class Base
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
     * 方法对应的HTTP请求方式
     * @var array
     */ 
    private $_requestMethod = [
        'write' => 'PUT',
        'writeStream' => 'PUT',
    ];

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
     * 获取文件的mime type
     * TODO to be completed
     * @return string
     */ 
    protected function getMimeType()
    {
        return 'binary/octet-stream';
    }

    /**
     * 根据方法名获取对应的HTTP请求方式
     *
     * @param string $func 方法名，如：write，update等
     *
     * @return string
     * @throws \Namet\Oss\OssException
     */ 
    protected function getRequestMethod($func)
    {
        if (!isset($this->_requestMethod[$func])) {
            $this->throws('不存在的方法');
        }

        return $this->_requestMethod[$func];
    }

    /**
     * 抛出异常
     *
     * @param string $msg  异常信息
     * @param int    $code 错误码
     *
     * @throws \Namet\Oss\OssException
     */ 
    protected function throws($msg, $code = 0)
    {
        throw new OssException($msg, $code);
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


}


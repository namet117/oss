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
     * @var Config|array
     */ 
    protected $config = array();

    /**
     * @var Client
     */ 
    private $_client = null;

    /**
     * 方法对应的HTTP请求方式
     * @var array
     */ 
    private $_requestMethod = array(
        'write' => 'PUT',
        'writeStream' => 'PUT',
    );

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
     */ 
    protected function getRequestMethod($func)
    {
        if (!isset($this->_requestMethod[$func])) {
            return false;
        }

        return $this->_requestMethod[$func];
    }

    /**
     * 创建用来加密的字符串
     *
     * @param array $params
     *
     * @return string
     */ 
    protected function makeStringToSign(array $params)
    {
        return "{$params['method']}\n\n{$params['mime_type']}\n{$params['date']}\n" . 
            "/{$this->config->bucket}/{$params['filename']}";
    }

    /**
     * 加密签名
     *
     * @param string $string StringToSign
     *
     * @return string
     */ 
    protected function makeAuthorization($string)
    {
        $signature = base64_encode(hash_hmac('sha1', $string, $config->secret, true));
        $authorization = 'AWS '.  $this->config->key_id . ':' . $signature;

        return $authorization;
    }

    /**
     * 构造请求的Url
     *
     * @param array $params 请求的参数
     *
     * @return string
     */ 
    protected function makeRequestUrl(array $params)
    {
        if (preg_match('/^https?:\/\//', $this->config->endpoint)) {
            $endpoint = str_replace('://', "://{$bucket}.", $endpoint);
        } else {
            $endpoint = "https://{$bucket}." . $endpoint;
        }
        $url = $endpoint . ($filename ? "/{$filename}" : '');

        return $url;
    }

    /**
     * 抛出异常
     *
     * @param string $msg  异常信息
     * @param int    $code 错误码
     *
     * @throws OssException
     */ 
    protected function throws($msg, $code = 0)
    {
        throw new OssException($msg, $code);
    }

    /**
     * 获取HTTP客户端
     *
     * @return Client
     */ 
    protected function getClient()
    {
        if (!$this->_client) {
            $this->_client = new Client();
        }

        return $this->_client;
    }


}


<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss\Drivers;

use Guzzle\Http\Client;
use Namet\Oss\OssException;

abstract class Base
{
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
     *
     * @param mixed $content 内容
     *
     * @return string
     */
    protected function getMimeType($content)
    {
        // TODO 待扩展
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
            "/{$params['config']->bucket}/{$params['filename']}";
    }

    /**
     * 加密签名
     *
     * @param array $params
     *
     * @return string
     */
    protected function makeAuthorization(array $params)
    {
        $signature = base64_encode(hash_hmac('sha1', $params['string_to_sign'], $params['config']->secret, true));

        return "AWS{$params['config']->key_id}:{$signature}";
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
        if (preg_match('/^https?:\/\//', $params['config']->endpoint)) {
            $endpoint = str_replace('://', "://{$params['config']->bucket}.", $params['config']->endpoint);
        } else {
            $endpoint = "https://{$params['config']->bucket}." . $params['config']->endpoint;
        }
        $url = $endpoint . ($params['filename'] ? "/{$params['filename']}" : '');

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

    /**
     * 发起请求
     *
     * @param array $params 
     *
     * @return mixed
     */  
    protected function sendRequest(array $params)
    {
        // 如果不存在请求地址，则执行拼装
        if (empty($params['request_url']) {
            $params['request_url'] = $this->makeRequestUrl($params);
        }

        // 获取实例
        $client = $this->getClient();

        // 构造请求
        $headers = array();
    }

    protected function buildHeaders(array $params)
    {
        return array(
        
        );
    }
}


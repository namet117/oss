<?php

namespace Namet\Oss\Traits;

use GuzzleHttp\Client;


trait DriverTrait
{
    /**
     * 方法对应的HTTP请求方式
     * @var array
     */
    protected $requestMethod = [
        'write' => 'PUT',
        'writeStream' => 'PUT',
    ];

    /**
     * GuzzleHttp Client 实例
     *
     * @var Client
     */
    private $_client = null;

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
     * 根据方法名获取对应的HTTP请求方式
     *
     * @param string $func 方法名，如：write，update等
     *
     * @return string
     */
    protected function getRequestMethod($func)
    {
        if (!isset($this->requestMethod[$func])) {
            return false;
        }

        return $this->requestMethod[$func];
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

        return "AWS {$params['config']->key_id}:{$signature}";
    }

    /**
     * 获取Http Client实例
     *
     * @return Client
     */
    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * 构造headers
     *
     * @param array $params
     *
     * @return array
     */
    protected function buildHeaders(array $params)
    {
        return array(
            'Content-Type' => $params['mime_type'],
            'Accept' => 'application/json',
            'Date' => $params['date'],
            'Authorization' => $params['authorization'],
        );
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
        if (empty($params['request_url'])) {
            $params['request_url'] = $this->makeRequestUrl($params);
        }

        // 获取实例
        $client = $this->getClient();

        // 构造请求
        $headers = $this->buildHeaders($params);

        $request = $client->createRequest($params['method'], $params['request_url'], $headers, $params['body']);

        try {
            $response = $request->send();
            echo "success!! \n";
            echo $response->getBody(1);
        } catch (ClientErrorResponseException $e) {
            $response = $e->getResponse();
            echo "failed : \n";
            echo $response->getBody(1);
        }
        exit;
        return $response->getBody();
    }
}

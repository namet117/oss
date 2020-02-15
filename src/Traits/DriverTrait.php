<?php

namespace Namet\Oss\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

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
            "/{$params['config']->get('bucket')}/{$params['filename']}";
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
        $signature = base64_encode(hash_hmac(
            'sha1',
            $params['string_to_sign'],
            $params['config']->get('secret_key'),
            true
        ));

        return "AWS {$params['config']->get('access_key')}:{$signature}";
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
        if (preg_match('/^https?:\/\//', $params['config']->get('endpoint'))) {
            $endpoint = str_replace('://', "://{$params['config']->get('bucket')}.", $params['config']->get('endpoint'));
        } else {
            $endpoint = "https://{$params['config']->get('bucket')}." . $params['config']->get('endpoint');
        }

        return $endpoint . ($params['filename'] ? "/{$params['filename']}" : '');
    }


    /**
     * 获取Http Client实例
     *
     * @return Client
     */
    public function getClient()
    {
        if (is_null($this->_client)) {
            $this->_client = new Client();
        }

        return $this->_client;
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

        try {
            $headers = $this->buildHeaders($params);
            $body = isset($params['body']) ? $params['body'] : null;
            $request = new Request($params['method'], $params['request_url'], $headers, $body);

            $response = $client->send($request);

            return $response->getBody(true);
        } catch (RequestException $e) {
            $this->handleRequestException($e);
            $response = $e->getResponse();
            echo "failed : \n";
            $body = $response->getBody(true);
            var_dump($body);
        }
        exit;
        return $response->getBody();
    }

    protected function handleRequestException(RequestException $e)
    {
        // 若没有响应，则原样抛出异常
        if (!$e->hasResponse()) {
            throw $e;
        }


    }
}

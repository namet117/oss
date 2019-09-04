<?php

use Guzzle\Http\Exception\ClientErrorResponseException;

require __DIR__ . '/base.php';

class Obs extends base
{
    /**
     * @var \League\Flysystem\Config
     */
    private $_config;

    public function __construct()
    {
        $this->_config = $this->getConfig('obs');
    }

    public function putObject()
    {
        $filename = 'namet-test-file-2';
        $content = 'This is namet test file, Hello Obs!';

        $method = 'PUT';

        $date = $this->getDate();
        $mimeType = $this->getMimeType($content);

        $bucket = $this->_config->get('bucket');
        $endpoint = $this->_config->get('endpoint');

        $stringToSign = "{$method}\n\n{$mimeType}\n{$date}\n/{$bucket}/{$filename}";

        // var_dump($stringToSign); 

        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $this->_config->get('secret'), true));
        $authorize = 'AWS '.  $this->_config->get('key_id') . ':' . $signature;

        $client = $this->getRequestClient();

        $request_url = $this->buildRequestUrl($endpoint, $bucket, $filename);

        $headers = array(
            'Content-Type' => 'binary/octet-stream',
            'Date' => $date,
            'Authorization' => $authorize,
        );

        $request = $client->createRequest($method, $request_url, $headers, $content);

        try {
            $response = $request->send();
        } catch (ClientErrorResponseException $e) {
            $response = $e->getResponse();
        }
        var_dump((string)$response->getBody());
    }
}

$obs = new Obs();

$obs->putObject();


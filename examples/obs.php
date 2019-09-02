<?php

require __DIR__ . '/base.php';

class Obs extends base
{
    private $_config = array();

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

        $stringToSign = "{$method}\n\n{$mimeType}\n{$date}\n/{$this->_config['bucket']}/{$filename}";

        // var_dump($stringToSign); 
        
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $this->_config['secret'], true));
        $authorize = 'AWS '.  $this->_config['key_id'] . ':' . $signature;

        var_dump($signature);

        $method = strtolower($method);
    }
}

$obs = new Obs();

$obs->putObject();


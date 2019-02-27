<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 17:10
 */

namespace Namet\Oss\Drivers;


use Namet\Oss\DriverBase;
use Namet\Oss\DriverInterface;
use Oss\OssClient;
use Oss\Core\OssException;

class Oos extends DriverBase implements DriverInterface
{
    public function connect()
    {
        $config = $this->config;

        try {
            $this->connect = new OssClient($config->key_id, $config->secret, $config->endpoint);
        } catch(OssException $e) {
           $this->throwException($e); 
        }
    }

    public function upload($local_file, $path)
    {
        if (empty($local_file) || empty($path)) {
            $this->throwException('参数错误');
        }

        $this->checkLocalFile($local_file);


        
    }
}


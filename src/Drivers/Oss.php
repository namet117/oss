<?php
/**
 * Created by PhpStorm.
 * User: namet117<namet117@163.com>
 * DateTime: 2018/9/10 23:57
 */

namespace Namet\Oss\Drivers;


use Namet\Oss\DriverBase;
use OSS\Core\OssException;
use OSS\OssClient;

class Oss extends DriverBase
{
    public function connect($config = array())
    {
        $config = $this->config();
        try {
            $client = new OssClient($config->key_id, $config->secret, $config->endpoint, !empty($config->cname));
            $this->connection = $client;
            return true;
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }
}

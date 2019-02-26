<?php
/**
 * Created by PhpStorm.
 * User: namet117<namet117@163.com>
 * DateTime: 2018/9/10 23:57
 */

namespace Namet\Oss\Drivers;


use Namet\Oss\DriverBase;
use Namet\Oss\DriverInterface;
use OSS\Core\OssException;
use OSS\OssClient;

class Oss extends DriverBase implements DriverInterface
{
    protected $_connection;

    public function connect($config = array())
    {
        try {
            // true为开启CNAME。CNAME是指将自定义域名绑定到存储空间上。
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint, true);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }
}

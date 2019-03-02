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
    /**
     * @var null|\OSS\OssClient
     */
    private $_connection = null;

    /**
     * @var string
     */
    private $_bucket = '';

    /**
     * 统一调用方法
     *
     * @param $function
     * @param $args
     *
     * @return mixed
     * @throws \Namet\Oss\OssException
     */
    public function invoke($function, $args)
    {
        try {
            return parent::invoke($function, $args);
        } catch (OssException $e) {
            $this->_throw($e);
        }
    }

    /**
     * 连接
     *
     * @param array $config
     *
     * @return bool
     * @throws \Namet\Oss\OssException
     */
    public function connect($config = array())
    {
        $config = $this->_config();
        $this->_bucket = $config->bucket;
        try {
            $client = new OssClient($config->key_id, $config->secret, $config->endpoint, !empty($config->cname));
            $this->_connection = $client;
            return true;
        } catch (OssException $e) {
            $this->_throw($e);
        }
    }

    public function upload($file, $org)
    {
        $this->_checkLocalFile($org);
        $this->_connection->uploadFile($this->_bucket, $file, $org);
        return true;
    }

    public function exists($file)
    {
        return $this->_connection->doesObjectExist($this->_bucket, $file);
    }

    public function delete($files)
    {
        $files = is_array($files) ? $files : array($files);
        $this->_connection->deleteObjects($this->_bucket, $files);
    }

    public function url($file)
    {
        $separate = preg_match('/^\//', $file) ? '' : '/';
        $config = $this->_config();
        $url = !empty($config->cname) ? $config->cname : "https://{$config->bucket}.{$config->endpoint}";
        return "{$url}{$separate}{$file}";
    }

    public function move($old, $new)
    {
        $this->copy($old, $new);
        $this->delete($old);
    }

    public function copy($old, $new)
    {
        $this->_connection->copyObject($this->_bucket, $old, $this->_bucket, $new);
    }
}

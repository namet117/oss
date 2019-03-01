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

    public function close()
    {
        $this->_connection = null;
    }

    /**
     * 上传单个文件到OSS
     *
     * @param string $file 目标地址
     * @param string $org 原文件地址
     *
     * @throws \Namet\Oss\OssException
     * @throws \OSS\Core\OssException
     */
    public function upload($file, $org)
    {
        $this->_checkLocalFile($org);
        $this->_connection->uploadFile($this->_bucket, $file, $org);
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

    public function url($file);

    public function size($file);

    public function lastModified($file);
}

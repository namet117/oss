<?php
/**
 * Created by PhpStorm.
 * User: namet117<namet117@163.com>
 * DateTime: 2018/9/10 23:57
 */

namespace Namet\Oss\Drivers;

use League\Flysystem\Config;
use Namet\Oss\DriverBase;
use OSS\Core\OssException;
use OSS\OssClient;

class Oss extends DriverBase
{
    /**
     * @const  VISIBILITY_PUBLIC  public visibility
     */
    const VISIBILITY_PUBLIC = 'public-read';

    /**
     * @const  VISIBILITY_PRIVATE  private visibility
     */
    const VISIBILITY_PRIVATE = 'private';

    /**
     * @var null|\OSS\OssClient
     */
    private $_connection = null;

    /**
     * @var string
     */
    private $_bucket = '';

    /**
     * Oss constructor.
     *
     * @param array $config
     *
     * @throws \Namet\Oss\OssException
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $config = $this->_config();
        $this->_bucket = $config->bucket;
        try {
            $client = new OssClient($config->key_id, $config->secret, $config->endpoint, !empty($config->cname));
            $this->_connection = $client;
        } catch (OssException $e) {
            $this->_throw($e);
        }
    }

    public function writeStream($path, $resource, Config $config)
    {
        $content = stream_get_contents($resource);

        return $this->write($path, $content, $config);
    }

    public function write($path, $content, Config $config)
    {
        try {
            $this->_connection->uploadFile($this->_bucket, $path, $content);
        } catch (OssException $e) {
            $this->_throw($e);
        }
        return true;
    }
    public function updateStream($path, $resource, Config $config)
    {
        return $this->writeStream($path, $resource, $config);
    }

    public function update($path, $content, Config $config)
    {
        return $this->write($path, $content, $config);
    }

    public function has($path)
    {
        return $this->_connection->doesObjectExist($this->_bucket, $path);
    }

    public function delete($path)
    {
        try {
            return $this->_connection->deleteObject($this->_bucket, $path);
        } catch (OssException $e) {
            $this->_throw($e);
        }
    }

    public function getUrl($path)
    {
        $separate = preg_match('/^\//', $path) ? '' : '/';
        $config = $this->_config();
        $url = !empty($config->cname) ? $config->cname : "https://{$config->bucket}.{$config->endpoint}";
        return "{$url}{$separate}{$path}";
    }

    public function rename($old, $new)
    {
        $this->copy($old, $new);
        $this->delete($old);
    }

    public function copy($old, $new)
    {
        try {
            return $this->_connection->copyObject($this->_bucket, $old, $this->_bucket, $new);
        } catch (OssException $e) {
            $this->_throw($e);
        }
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($dirname)
    {
        return $this->delete($dirname);
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     *
     * @return array|false
     */
    public function createDir($dirname, Config $config)
    {
        // 不需要实现
        return true;
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array
     * @throws \Namet\Oss\OssException
     */
    public function setVisibility($path, $visibility)
    {
        $map = ['default', 'private', 'public-read', 'public-read-write'];

        if (!in_array($visibility, $map)) {
            $this->_throw("文件权限设置错误，可选值为：" . implode(',', $map));
        }

        try {
            return $this->_connection->putObjectAcl($this->_bucket, $path, $visibility);
        } catch (OssException $e) {
            $this->_throw($e);
        }
    }
}

<?php

namespace Namet\Oss\Drivers;

use Namet\Oss\Config;
use Namet\Oss\Interfaces\BucketInterface;
use Namet\Oss\Interfaces\DriverInterface;


abstract class DriverBase extends Base implements DriverInterface, BucketInterface
{
    /**
     * 写入字符串
     *
     * @param string $path
     * @param string $contents
     * @param Config $config
     *
     * @return array|false|void
     */
    public function write($path, $contents, Config $config)
    {
        $params = array(
            'date' => $this->getDate(),
            'method' => $this->getRequestMethod(__FUNCTION__),
            'mime_type' => $this->getMimeType($contents),
            'bucket' => $this->config->bucket,
            'filename' => $path,
        );

        $stringToSign = $this->makeStringToSign($params);

        $authorization = $this->makeAuthorization($stringToSign);

        $request_url = $this->makeRequestUrl($params);
    }

    public function writeStream($path, $resource, Config $config)
    {
        // TODO: Implement writeStream() method.
    }

    public function update($path, $contents, Config $config)
    {
        // TODO: Implement update() method.
    }

    public function updateStream($path, $resource, Config $config)
    {
        // TODO: Implement updateStream() method.
    }

    public function getUrl($path)
    {
        // TODO: Implement getUrl() method.
    }

    public function upload($path, $local)
    {
        // TODO: Implement upload() method.
    }

    public function has($path)
    {
        // TODO: Implement has() method.
    }

    public function copy($path, $newpath)
    {
        // TODO: Implement copy() method.
    }

    public function createDir($dirname, Config $config)
    {
        // TODO: Implement createDir() method.
    }

    public function delete($path)
    {
        // TODO: Implement delete() method.
    }

    public function deleteDir($dirname)
    {
        // TODO: Implement deleteDir() method.
    }

    public function rename($path, $newpath)
    {
        // TODO: Implement rename() method.
    }

    public function setVisibility($path, $visibility)
    {
        // TODO: Implement setVisibility() method.
    }
}

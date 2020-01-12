<?php

namespace Namet\Oss\Drivers;


use League\Flysystem\Config;
use League\Flysystem\FileNotFoundException;
use Namet\Oss\Interfaces\BucketInterface;
use Namet\Oss\Interfaces\ObjectInterface;
use Namet\Oss\OssException;
use Namet\Oss\Traits\DriverTrait;


abstract class DriverBase implements ObjectInterface, BucketInterface
{
    use DriverTrait;


    /**
     * 配置信息
     *
     * @var Config $config
     */
    protected $config;

    /**
     * DriverBase constructor.
     *
     * @param Config|null $config
     */
    public function __construct(Config $config = null)
    {
        $this->config = $config;
    }

    public function write($path, $contents, Config $config)
    {
        $params = [
            'date' => $this->getDate(),
            'method' => $this->getRequestMethod(__FUNCTION__),
            'mime_type' => $this->getMimeType($contents),
            'body' => $contents,
            'config' => $config,
            'filename' => $path,
        ];

        $params['string_to_sign'] = $this->makeStringToSign($params);
        $params['authorization'] = $this->makeAuthorization($params);

        $response = $this->sendRequest($params);

        var_dump((string)$response->getBody());
        exit;
    }

    public function writeStream($path, $resource, Config $config)
    {

    }

    /**
     * Read a file.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return string|false The file contents or false on failure.
     */
    public function read($path)
    {
    }

    /**
     * Retrieves a read-stream for a path.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return resource|false The path resource or false on failure.
     */
    public function readStream($path)
    {
    }

    // 有则更新，无则抛异常
    public function update($path, $contents, Config $config)
    {
        if (!$this->has($path)) {
            OssException::throws("{$path} 不存在");
        }

        return $this->write($path, $contents, $config);
    }

    // 有则更新，无则抛异常
    public function updateStream($path, $resource, Config $config)
    {
        if (!$this->has($path)) {
            OssException::throws("{$path} 不存在");
        }

        return $this->writeStream($path, $resource, $config);
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function rename($path, $newpath)
    {

    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($path, $newpath)
    {

    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {

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

    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility)
    {

    }


    public function getSize($path)
    {
        // TODO: Implement getSize() method.
    }


    public function getVisibility($path)
    {
        // TODO: Implement getVisibility() method.
    }

    public function has($path)
    {
        // TODO: Implement has() method.
    }

    public function listContents($directory = '', $recursive = false)
    {
        // TODO: Implement listContents() method.
    }

    public function getMetadata($path)
    {
        // TODO: Implement getMetadata() method.
    }

    public function getMimetype($path)
    {
        // TODO: Implement getMimetype() method.
    }


    public function getTimestamp($path)
    {
        // TODO: Implement getTimestamp() method.
    }

    public function getUrl($path)
    {
        // TODO Implement getUrl() method
    }
}

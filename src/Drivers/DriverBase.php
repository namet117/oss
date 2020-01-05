<?php

namespace Namet\Oss\Drivers;


use League\Flysystem\Config;
use League\Flysystem\FileNotFoundException;
use Namet\Oss\Interfaces\BucketInterface;
use Namet\Oss\Interfaces\ObjectInterface;
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

    public function write($path, $contents, Config $config)
    {

    }

    public function writeStream($path, $resource, Config $config)
    {

    }

    public function update($path, $contents, Config $config)
    {

    }

    public function updateStream($path, $resource, Config $config)
    {

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
}

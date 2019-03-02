<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

/**
 * Interface DriverInterface
 *
 * @package Namet\Oss
 *
 * @method bool setConfig(array $config) 设置配置
 */
interface DriverInterface
{
    public function connect();

    public function upload($file, $resource);

    public function exists($file);

    public function delete($file);

    public function url($file);

    public function move($old, $new);

    public function copy($old, $new);

//    public function close();

//    public function size($file);

//    public function lastModified($file);

//    public function setPublic($file);

//    public function setPrivate($file);

//    public function files($directory);

//    public function allFiles($directory);

//    public function directories($directory);

//    public function allDirectories($directory);
}

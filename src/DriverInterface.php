<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

interface DriverInterface
{
    public function connect($config = array());

    public function close();

//    public function put($file, $content);
//
//    public function add($file, $content);

    public function exists($file);

    public function get($file);

    public function delete($file);

    public function url($file);

    public function size($file);

    public function lastModified($file);

    public function putFile($file, $resource);

//    public function move($old, $new);

//    public function copy($old, $new);

//    public function setPublic($file);
//
//    public function setPrivate($file);
//
//    public function files($directory);
//
//    public function allFiles($directory);
//
//    public function directories($directory);
//
//    public function allDirectories($directory);
}

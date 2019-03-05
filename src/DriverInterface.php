<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

use League\Flysystem\AdapterInterface;

/**
 * Interface DriverInterface
 *
 * @package Namet\Oss
 */
interface DriverInterface extends AdapterInterface
{
    /**
     * 获取文件地址
     *
     * @param  string $path 文件路径
     * @return string
     */
    public function getUrl($path);
}

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
    public function getUrl($path);
}

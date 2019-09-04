<?php
/**
 * Author: namet<namet117@163.com>
 * Date: 2019/9/3 21:22
 */

namespace Namet\Oss;

use \League\Flysystem\Config as BaseConfig;

/**
 * Class Config
 * @package Namet\Oss
 *
 * @var string key_id
 */
class Config extends BaseConfig
{
    /**
     * 魔术方法
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}

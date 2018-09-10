<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

abstract class DriverBase
{
    private $_isReady = false;

    public function __construct()
    {

    }

    public function isReady()
    {
        return $this->_isReady;
    }

    public function init($config = array())
    {
        $this->_isReady = true;
    }
}

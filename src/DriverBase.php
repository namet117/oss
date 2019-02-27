<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

use Namet\Oss\OssException;

abstract class DriverBase
{
    /**
     * 连接信息
     */
    protected $_connection = null;

    protected $_config = array();

    public function __construct()
    {

    }

    public function init($config = array())
    {
        $this->_isReady = true;
    }

    /**
     * 抛出异常
     *
     * @param \Exception $e
     *
     * @throws \Namet\Oss\OssException
     */
    protected function throwException(\Exception $e)
    {
        throw new OssException($e);
    }

    protected function checkLocalFile($file)
    {
        
    }
}

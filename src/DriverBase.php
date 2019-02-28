<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

abstract class DriverBase implements DriverInterface
{
    /**
     * 连接信息
     */
//    protected $connection = null;

    protected $_config;

    public function __construct($config)
    {
        $this->_config = json_decode(json_encode($config));
        $this->connection();
    }

    protected function config()
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

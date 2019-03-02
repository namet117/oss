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

    protected $_config = array();

    /**
     * 检查配置文件
     * @throws \Namet\Oss\OssException
     */
    protected function _checkConfig()
    {
        $keys = array('key_id', 'secret', 'bucket');
        foreach ($keys as $key) {
            if (empty($this->_config()->$key)) {
                $this->_throw("配置中字段「{$key}」不可为空");
            }
        }
    }

    /**
     * 获取配置文件
     *
     * @return \stdClass
     */
    protected function _config()
    {
        return $this->_config;
    }

    /**
     * 设置配置信息
     *
     * @param $config
     *
     * @throws \Namet\Oss\OssException
     */
    public function setConfig($config)
    {
        $this->_config = json_decode(json_encode($config));
        $this->_checkConfig();
        $this->connect();
    }

    public function invoke($function, $args)
    {
        return call_user_func_array(array($this, $function), $args);
    }

    /**
     * 抛出异常
     *
     * @param mixed $e
     *
     * @throws \Namet\Oss\OssException
     */
    protected function _throw($e)
    {
        throw new OssException($e);
    }

    /**
     * 检查本地文件是否存在
     *
     * @param string $file 文件名
     *
     * @throws \Namet\Oss\OssException
     */
    protected function _checkLocalFile($file)
    {
        if (!file_exists($file)) {
            $this->_throw("本地文件{$file}不存在");
        }
        // TODO 还需要检查文件大小是否已经超过5G
    }
}

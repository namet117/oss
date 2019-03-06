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

    /**
     * @var null|\stdClass
     */
    private $_config;

    /**
     * 配置文件中的检查项
     * @var array
     */
    protected $_fieldsInConfigToCheck = ['key_id', 'secret', 'bucket'];

    protected $_orgResponse = [];

    /**
     * DriverBase constructor.
     *
     * @param array $config 配置
     *
     * @throws \Namet\Oss\OssException
     */
    public function __construct($config)
    {
        $this->_config = json_decode(json_encode($config));
        $this->_checkConfig();
    }

    /**
     * 检查配置文件
     * @throws \Namet\Oss\OssException
     */
    protected function _checkConfig()
    {
        $keys = $this->_fieldsInConfigToCheck;
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
}

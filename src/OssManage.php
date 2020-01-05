<?php
/**
 * Author: nameT<namet117@163.com>
 * DateTime: 2019/12/26 01:02
 */

namespace Namet\Oss;

use Namet\Oss\Drivers\Obs;
use Namet\Oss\Interfaces\BucketInterface;
use Namet\Oss\Interfaces\ObjectInterface;
use League\Flysystem\Config;


class OssManage
{
    /**
     * 当前可用的驱动列表
     *
     * @var array
     */
    private static $_availableDrivers = [
        // 华为云
        'obs' => Obs::class,
    ];

    /**
     * 当前缓存的驱动实例
     *
     * @var array
     */
    private $_instanceCache = [];

    /**
     * 当前实例
     *
     * @var Namet\Oss\Drivers\DriverBase
     */
    private $_currentDriver;

    /**
     * 当前配置信息
     *
     * @var Config
     */
    private $_currentConfig;

    /**
     * 当前配置信息
     *
     * @var Config
     */
    private $_config;

    public function __construct($driver, array $config)
    {
        $this->_currentConfig = new Config($config);
        $this->_currentDriver = $this->_createDriverInstance($driver);
    }

    /**
     * 扩展自定义驱动器
     *
     * @author nameT<namet117@163.com>
     *
     * @param string $name  驱动器名称
     * @param string $class 驱动器类名
     *
     * @return void
     * @throws OssException
     */
    public function extend($name, $class)
    {
        $name = trim($name);
        if ($this->_getDriverClass($name)) {
            OssException::throws('驱动名已存在，请更换');
        }
        $instance = new $class();
        if (!$instance instanceof BucketInterface || !$instance instanceof ObjectInterface) {
            OssException::throws(
                '自定义驱动器须实现接口：' . '\\Namet\\Oss\\Interfaces\\BucketInterface和\\Namet\\Oss\\Interfaces\\ObjectInterface'
            );
        }

        self::$_availableDrivers[$name] = $class;
    }

    /**
     * 调用方法
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $arguments[] = $this->_currentConfig;

        return call_user_func_array([$this->_currentDriver, $name], $arguments);
    }

    /**
     * 检查驱动是否存在
     *
     * @author nameT<namet117@163.com>
     *
     * @param string $driver 驱动名
     *
     * @return false|string
     */
    private function _getDriverClass($driver)
    {
        return isset(self::$_availableDrivers[$driver]) ? self::$_availableDrivers[$driver] : false;
    }

    /**
     * 创建驱动实例
     *
     * @param string $driver 驱动名
     *
     * @return void
     */
    private function _createDriverInstance($driver)
    {
        if (!$class = $this->_getDriverClass($driver)) {
            OssException::throws('不存在的驱动：' . $driver);
        }

        $this->_currentDriver = new $class($this->_currentConfig);
    }

    private function _getDriverName()
    {
        return is_string(self::$_availableDrivers)
            ? false
            : array_search('\\' . get_class(self::$_availableDrivers), self::$$_availableDrivers);
    }
}

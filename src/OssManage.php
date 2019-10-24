<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

use Namet\Oss\Config;
use Namet\Oss\Drivers\DriverBase;
use Namet\Oss\Interfaces\BucketInterface;
use Namet\Oss\Interfaces\ObjectInterface;


/**
 * Class OssManage
 *
 * @package Namet\Oss
 *
 * @method string getUrl(string $path) 获取文件链接
 * @method bool upload(string $path, string $local) 上传本地文件
 * @method true|array writeStream(string $path, resource $resource) 将文件流上传到OSS中
 * @method true|array write(string $path, string $contents) 将文件上传到OSS中
 * @method true|array updateStream(string $path, resource $resource) 将文件流更新到OSS中去
 * @method true|array update(string $path, string $content) 将文件更新到OSS中去
 * @method bool has(string $path) 判断文件是否存在
 * @method bool delete(string $path) 删除文件
 * @method bool rename(string $path, string $new_path) 重命名文件
 * @method bool copy(string $path, string $new_path) 拷贝文件
 * @method bool deleteDir(string $path) 删除文件夹
 * @method bool setVisibility(string $path, string $visibility = 'public') 设置文件权限
 * @method string read(string $path) 读取文件内容到内存中
 */
class OssManage
{
    /**
     * 内置的可用驱动
     * @var array
     */
    private static $_drivers = array(
        // 阿里云
//        'oss' => '\\Namet\\Oss\\Drivers\\Oss',
        // 华为云
        'obs' => '\\Namet\\Oss\\Drivers\\Obs',
//        'cos' => '\\Namet\\Oss\\Drivers\\Cos',
//        'nos' => '\\Namet\\Oss\\Drivers\\Nos',
//        'oos' => '\\Namet\\Oss\\Drivers\\Oos',
//        'qos' => '\\Namet\\Oss\\Drivers\\Qos',
//        'ufile' => '\\Namet\\Oss\\Drivers\\Ufile',
    );

    /**
     * 已生成的类实例
     * @var array
     */
    private static $_instance = array();

    /**
     * 当前OSS驱动实例
     *
     * @var string|DriverBase
     */
    private $_driver = '';

    /**
     * 当前OSS的配置信息
     * @var array|Config
     */
    private $_config = array();

    /**
     * 所有驱动配置信息，比如 ['oss' => [配置信息...], 'oos' => [配置信息...]]
     * @var array
     */
    private $_driverConfig = array();

    /**
     * OssManage constructor.
     *
     * @param string $driver 驱动名称，可选值：oss/bos/cos/nos/qos/oos/ufile
     * @param array  $config 配置信息
     *
     * @return void
     */
    public function __construct($driver = '', $config = array())
    {
        $driver && $this->driver($driver);
        $config && $this->config($config);
    }

    /**
     * 设置OSS配置
     *
     * @param $config
     *
     * @return $this
     */
    public function config(array $config)
    {
        if (!$this->_driver) {
            OssException::throws('请先传入driver');
        }
        $this->_config = $config;

        $this->_getDriverInstance();

        return $this;
    }

    /**
     * 设置驱动
     *
     * @param string $name 驱动名
     *
     * @return $this
     */
    public function driver($name)
    {
        if (!isset(self::$_drivers[$name])) {
            OssException::throws("驱动：{$name} 不存在！");
        }
        $this->_driver = $name;

        return $this;
    }

    /**
     *  扩展驱动类
     *
     * @param string $name  驱动名称
     * @param mixed  $class 驱动类
     *
     * @return $this
     */
    public function extend($name, $class)
    {
        if (isset(self::$_drivers[$name])) {
            OssException::throws("驱动名：{$name} 已存在，请更换！");
        }
        $instance = is_object($class) ? $class : new $class;
        if (!$instance instanceof ObjectInterface/* || !$instance instanceof BucketInterface*/) {
            OssException::throws(
                '驱动类：' . get_class($class) . '须实现Namet\\Oss\\Interfaces\\BucketInterface'
                    . '和Namet\\Oss\\Interfaces\\BucketInterface接口类'
            );
        }

        return $this;
    }

    /**
     * 调用Driver的方法
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // 多传入一个Config信息
        $arguments[] = $this->_getDriverConfig();

        // 调用方法
        return call_user_func_array(array($this->_driver, $name), $arguments);
    }

    /**
     * 获取Driver实例
     *
     * @return void
     */
    private function _getDriverInstance()
    {
        // 获取驱动名称
        $driver_name = $this->_getDriverName();
        // 获取已经存在的配置信息
        $old_config = $this->_getDriverConfig(true);
        // 排序配置信息
        ksort($this->_config);
        // 当传入的config不同时要重新获取实例
        if (!isset(self::$_instance[$driver_name]) || json_encode($this->_config) != json_encode($old_config)) {
            // 保存当前的配置
            $this->_driverConfig[$driver_name] = new Config($this->_config);
            // 驱动类名
            $class = self::$_drivers[$driver_name];
            // 获取实例
            $instance = new $class($this->_driverConfig[$driver_name]);
            // 保存实例
            self::$_instance[$driver_name] = $instance;
        }

        // 获取实例到当前对象
        $this->_driver = self::$_instance[$driver_name];
        // 清空传入的配置信息
        $this->_config = array();
    }

    /**
     * 获取驱动名称
     *
     * @return string
     */
    private function _getDriverName()
    {
        return is_string($this->_driver)
            ? $this->_driver
            : array_search('\\' . get_class($this->_driver), self::$_drivers);
    }

    /**
     * 获取当前驱动的配置文件
     *
     * @param bool $array 是否返回数组格式
     *
     * @return array
     */
    private function _getDriverConfig($array = false)
    {
        $config =  isset($this->_driverConfig[$this->_getDriverName()])
            ? $this->_driverConfig[$this->_getDriverName()]
            : array();

        return $config ? ($array ? $config->original() : $config) : array();
    }
}

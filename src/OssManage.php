<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

use League\Flysystem\Config;

/**
 * Class OssManage
 *
 * @package Namet\Oss
 *
 * @method string getUrl(string $path) 获取文件链接
 * @method bool upload(string $path, string $local) 上传本地文件
 * @method true|array writeStream(string $path, resource $resource) 将文件流上传到OSS中
 * @method true|array write(string $path, resource $resource) 将文件上传到OSS中
 * @method true|array updateStream(string $path, resource $resource) 将文件流更新到OSS中去
 * @method true|array update(string $path, resource $resource) 将文件更新到OSS中去
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
        'oss' => '\\Namet\\Oss\\Drivers\\Oss',
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
    private static $_instance = [];

    /**
     * 当前OSS驱动实例
     * @var null|\Namet\Oss\DriverInterface
     */
    private $_driver = null;

    /**
     * 所有驱动配置信息，比如 ['oss' => [配置信息...], 'oos' => [配置信息...]]
     * @var array
     */
    private $_driverConfig = [];

    /**
     * OssManage constructor.
     *
     * @param string $driver 驱动名称，可选值：oss/bos/cos/nos/qos/oos/ufile
     * @param array  $config 配置信息
     *
     * @throws \Namet\Oss\OssException
     */
    public function __construct($driver = '', $config = [])
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
     * @throws \Namet\Oss\OssException
     */
    public function config($config)
    {
        $this->_checkIsReady();
        $this->_getDriverInstance($config);

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
            $this->_throw("驱动：{$name} 不存在！");
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
     * @throws \Namet\Oss\OssException
     */
    public function extend($name, $class)
    {
        if (isset(self::$_drivers[$name])) {
            $this->_throws("驱动名：{$name} 已存在，请更换！");
        }
        $instance = is_object($class) ? $class : new $class;
        if (!$instance instanceof DriverInterface) {
            $this->_throws('驱动类：' . get_class($class) . '未实现\\Namet\\Oss\\DriverInterface接口类');
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
     * @throws \Namet\Oss\OssException
     */
    public function __call($name, $arguments)
    {
        $this->_checkIsReady(true);

        // var_dump(get_class($this->_driver));
        // var_dump();
        // exit;

        $config = new Config($this->_getDriverConfig());
        $arguments[] = $config;
        return call_user_func_array([$this->_driver, $name], $arguments);
    }

    /**
     * 获取Driver实例
     *
     * @param array $config 配置信息
     *
     * @return void
     * @throws Namet\Oss\OssException
     */
    private function _getDriverInstance($config = [])
    {
        // 获取驱动名称
        $driver_name = $this->_getDriverName();
        // 获取已经存在的配置信息
        $old_config = json_encode(empty($this->_driverConfig) ? [] : $this->_driverConfig);
        // 排序配置信息
        ksort($config);
        if (!isset(self::$_instance[$driver_name]) || json_encode($config) != json_encode($old_config)) {
            $instance = new self::$_drivers[$driver_name]($config);
            self::$_instance[$driver_name] = $instance;
        }

        $this->_driver = self::$_instance[$driver_name];
        $this->_driverConfig[$driver_name] = $config;
    }

    /**
     * 抛出异常
     *
     * @param string $msg  错误信息
     * @param int    $code 错误码
     *
     * @throws \Namet\Oss\OssException
     */
    private function _throws($msg, $code = 0)
    {
        throw new OssException($msg, $code);
    }

    /**
     * 判断是否驱动已设置
     *
     * @param bool $all 是否检查全部
     *
     * @return bool
     * @throws \Namet\Oss\OssException
     */
    private function _checkIsReady($all = false)
    {
        if (empty($this->_driver)) {
            $this->_throws('请先设置驱动！');
        }
        $driver_name = $this->_getDriverName();

        if ($all && !is_object($this->_driver) && empty($this->_driverConfig[$driver_name])) {
            $this->_throws('请传入驱动配置！');
        }
        return true;
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
     * @return array
     */
    private function _getDriverConfig()
    {
        return $this->_driverConfig[$this->_getDriverName()];
    }
}

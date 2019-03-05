<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

/**
 * Class OssManage
 *
 * @package Namet\Oss
 *
 * @method bool upload(string $file, string $org)  上传文件
 * @method bool exists(string $file) 检查文件是否存在
 * @method bool delete(string $file) 删除文件
 * @method bool url(string $file) 获取文件地址
 * @method bool move(string $old, string $new) 移动文件
 * @method bool copy(string $old, string $new) 拷贝文件
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
        $driver_name = is_string($this->_driver) ? $this->_driver : array_search(get_class($this->_driver), self::$_drivers);
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
        $driver_name = is_string($this->_driver)
            ? $this->_driver
            : array_search(get_class($this->_driver), self::$_drivers);

        if ($all && empty($this->_driverConfig[$driver_name])) {
            $this->_throws('请传入驱动配置！');
        }
        return true;
    }
}

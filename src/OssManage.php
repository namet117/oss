<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */

namespace Namet\Oss;

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
    private static $_instance = array();

    /**
     * 当前OSS驱动实例
     * @var object
     */
    private $_oss;

    /**
     * 驱动配置文件
     * @var array
     */
    private $_config = array();

    /**
     * 允许通过魔术方法调用的方法列表
     * @var array
     */
    private $_allowed_functions = array(
        'exists', 'put', 'add', 'delete', 'url', 'size', 'lastModified', 'putFile', 'move', 'copy', 'setPublic',
        'setPrivate', 'files', 'allFiles', 'directories', 'allDirectories'
    );

    /**
     * OssManage constructor.
     *
     * @param string $driver 驱动名称，可选值：oss/bos/cos/nos/qos/oos/ufile
     * @param array  $config 配置信息
     *
     * @throws \Namet\Oss\OssException\OssException
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
    public function config($config)
    {
        $this->_config =$config;

        return $this;
    }

    /**
     * 设置驱动
     *
     * @param $name
     *
     * @return $this
     * @throws \Namet\Oss\OssException\OssException
     */
    public function driver($name)
    {
        if (!isset(self::$_drivers[$name])) {
            $this->_throws('不存在的驱动：' . $name);
        }

        if (!isset(self::$_instance[$name])) {
            self::$_instance[$name] = new self::$_drivers[$name];
        }

        $this->_oss = self::$_instance[$name];

        return $this;
    }

    /**
     *  扩展驱动类
     *
     * @param string $name  驱动名称
     * @param mixed  $class 驱动类
     *
     * @return $this
     * @throws \Namet\Oss\OssException\OssException
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
     * @throws \Namet\Oss\OssException\OssException
     */
    public function __call($name, $arguments)
    {
        if (!in_array($name, $this->_allowed_functions)) {
            $this->_throws("不存在的方法：{$name}！");
        }
        if (!$this->_oss->isReady()) {
            $this->_oss->init($this->_config);
        }

        return call_user_func_array(array($this->_oss, $name), $arguments);
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
}

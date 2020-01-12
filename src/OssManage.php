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

/**
 * Class OssManage
 *
 * @package Namet\Oss
 *
 * @method void writeStream(string $path, resource $resource) 将文件流上传到OSS中
 * @method void write(string $path, string $contents) 将文件上传到OSS中
 * @method void updateStream(string $path, resource $resource) 将文件流更新到OSS中去
 * @method void update(string $path, string $content) 将文件更新到OSS中去
 * @method bool upload(string $path, string $local) 上传本地文件到OSS
 * @method bool has(string $path) 判断文件是否存在
 * @method bool delete(string $path) 删除文件
 * @method bool rename(string $path, string $new_path) 重命名文件
 * @method bool copy(string $path, string $new_path) 拷贝文件
 * @method bool deleteDir(string $path) 删除文件夹
 * @method bool setVisibility(string $path, string $visibility = 'public') 设置文件权限
 * @method string read(string $path) 读取文件内容到内存中
 * @method string getUrl(string $path) 获取文件链接
 */
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
        $this->_createDriverInstance($driver);
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

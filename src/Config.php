<?php
/**
 * Author: namet<namet117@163.com>
 * Date: 2019/9/3 21:22
 */

namespace Namet\Oss;


/**
 * Class Config
 *
 * Based on\League\Flysystem\Config
 *
 * @property string $key_id   各平台的app id
 * @property string $secret   各平台的密钥
 * @property string $bucket   桶名
 * @property string $endpoint 桶地址
 * @property string $cname    自定义域名
 *
 * @package Namet\Oss
 *
 * @var string key_id
 */
class Config
{
    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var Config|null
     */
    protected $fallback;

    /**
     * Constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    /**
     * Get a setting.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed config setting or default when not found
     */
    public function get($key, $default = null)
    {
        if (!array_key_exists($key, $this->settings)) {
            return $this->getDefault($key, $default);
        }
        return $this->settings[$key];
    }

    /**
     * Check if an item exists by key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        if (array_key_exists($key, $this->settings)) {
            return true;
        }
        return $this->fallback instanceof Config
            ? $this->fallback->has($key)
            : false;
    }

    /**
     * Try to retrieve a default setting from a config fallback.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed config setting or default when not found
     */
    protected function getDefault($key, $default)
    {
        if (!$this->fallback) {
            return $default;
        }
        return $this->fallback->get($key, $default);
    }

    /**
     * Set a setting.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->settings[$key] = $value;
        return $this;
    }

    /**
     * Set the fallback.
     *
     * @param Config $fallback
     *
     * @return $this
     */
    public function setFallback(Config $fallback)
    {
        $this->fallback = $fallback;
        return $this;
    }

    /**
     * Magic function to get property
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}

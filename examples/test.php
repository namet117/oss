<?php

require '../vendor/autoload.php';

use Namet\Oss\OssManage;
use Namet\Oss\OssException;


define('NAMET-OSS', true);

$driver = 'obs';

$configs = include dirname(__FILE__) . '/config.php';


$config = $configs[$driver];


try {
    /** ----  1. 获取实例  ---- */
    // 获取实例
    $instance = new OssManage();
    $instance->driver($driver)->config($config);

    /** ----  2.基础用法  ---- */
    $path = 'test/test1234.txt';
    // 1. 将字符串内容写入文件
    $result = $instance->write($path, 'This is a Conetnt');

    echo "write string into file result: \n";
    var_dump($result);
exit;
    // 上传文件
    $local_file = './README.md';
    $instance->upload($path, $local_file);
    // 将文件流写入文件
    $instance->writeStream($path, $resource);
    // 删除文件
    $instance->delete($path);
    // 删除文件夹
    $instance->deleteDir($path);
    // 拷贝文件
    $new_path = 'test/test-123.txt';
    $instance->copy($path, $new_path);
    // 重命名文件
    $instance->rename($path, $new_path);
    // 判断文件是否存在 true/false
    $bool = $instance->has($path);
    // 获取文件链接（如果用户指定了自定义域名则会使用自定义域名反之则使用默认域名）
    $url = $instance->getUrl($path);
    // 将文件内容读到内存中
    $content = $instance->read($path);
    // 设置文件权限，可选值有： public/private
    $instance->setVisibility($path, 'public');
} catch (OssException $e) {
    // 所有操作失败都会抛出 Namet\Oss\OssException 异常
    echo $e->getMessage();
}


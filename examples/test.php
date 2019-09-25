<?php

require './vendor/autoload.php';

use Namet\Oss\OssManage;
use Namet\Oss\OssException;

try {
    // 配置文件
    $config = [
        // �access_token 详情请查询各服务商文档
        'key_id' => '',
        // 密钥
        'secret' => '',
        // �桶名
        'bucket' => '',
        // 节点地址
        'endpoint' => '',
        // 自定义域名
        'cname' => '',
    ];
    /** ----  1. 获取实例  ---- */
    // �初始化获取实例的时候传入参数
    $instance = new OssManage('oss', $config);
    // 也可以先获取实例，然后传入参数
    $instance = new OssManage();
    $instance->driver('oss')->config($config);

    /** ----  2.基础用法  ---- */
    $path = 'test/test1234.txt';
    // 上传文件
    $local_file = './README.md';
    $instance->upload($path, $local_file);
    // 将字符串内容写入文件
    $instance->write($path, 'This is a Conetnt');
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


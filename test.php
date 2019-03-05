<?php

require 'vendor/autoload.php';

$driver = 'oss';
$configs = include 'config.php';

$config = $configs[$driver];

try {
    $instance = new \Namet\Oss\OssManage($driver, $config);
    $dist = 'test/readme' . time() . '.md';
    $instance->write($dist, './README.md');
    $url = $instance->getUrl($dist);

    echo "{$url} \n";
} catch (\Namet\Oss\OssException $e) {
    echo "error: ";
    echo $e->getMessage(), "\n";
    echo $e->getFile(), ': ', $e->getLine(), "\n";
}

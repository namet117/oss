<?php

require 'vendor/autoload.php';

$driver = 'oss';
$configs = include 'config.php';

$config = $configs[$driver];

try {
    $instance = new \Namet\Oss\OssManage($driver, $config);
    $dist = 'test/readme.md';
    $instance->upload($dist, './README.md');
    $url = $instance->url($dist);

    echo "{$url} \n";
} catch (\Namet\Oss\OssException $e) {
    echo "error \n";
    echo $e->getMessage();
}

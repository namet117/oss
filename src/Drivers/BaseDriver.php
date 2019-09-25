<?php

namespace Namet\Oss\Drivers;

use Namet\Oss\Interfaces\BucketInterface;
use Namet\Oss\Interfaces\FileInterface;


class DriverBase extends Base implements FileInterface, BucketInterface
{
    public function write($path, $contents, Config $config)
    {
        $date = $this->getDate();
        $method = $this->getRequestMethod(__FUNCTION__);
        $mime_type = $this->getMimeType();
        $bucket = $this->config->bucket;
        $filename = $path;
    }
}

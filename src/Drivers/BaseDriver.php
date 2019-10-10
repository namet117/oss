<?php

namespace Namet\Oss\Drivers;

use Namet\Oss\Interfaces\BucketInterface;
use Namet\Oss\Interfaces\FileInterface;


class DriverBase extends Base implements FileInterface, BucketInterface
{
    public function write($path, $contents, Config $config)
    {
        $params = [
            'date' => $this->getDate(),
            'method' => $this->getRequestMethod(__FUNCTION__),
            'mime_type' => $this->getMimeType($contents),
            'bucket' => $this->config->bucket,
            'filename' => $path,
        ];

        $stringToSign = $this->makeStringToSign($params);

        $authorization = $this->makeAuthorization($stringToSign);

        $request_url = $this->makeRequestUrl($params);
    }
}

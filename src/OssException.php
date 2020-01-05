<?php
/**
 * Author: nameT<namet117@163.com>
 * DateTime: 2019/12/26 01:01
 */

namespace Namet\Oss;


class OssException extends \Exception
{
    /**
     * 抛出异常
     *
     * @param string     $msg
     * @param int        $code
     * @param \Throwable $previous
     *
     * @return void
     * @throws OssException
     */
    public static function throws($msg, $code = 0, $previous = null)
    {
        throw new OssException($msg, $code, $previous);
    }
}

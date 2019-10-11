<?php
/**
 * Author: namet117<namet117@163.com>
 * DateTime: 2018/8/21 13:24
 */
namespace Namet\Oss;


class OssException extends \Exception
{
    /**
     * 抛出异常
     *
     * @author namet117<namet117@163.com>
     *
     * @param string          $msg
     * @param int             $code
     * @param \Throwable|null $previous
     *
     * @throws \Namet\Oss\OssException
     */
    public static function throws($msg, $code = 0, $previous = null)
    {
        throw new OssException($msg, $code, $previous);
    }
}

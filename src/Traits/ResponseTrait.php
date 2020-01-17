<?php

namespace Namet\Oss\Traits;

trait ResponseTrait
{
    public function xml2array($xml)
    {
        $xml_object = simplexml_load_string($xml);

        $string = json_encode($xml_object);

        return json_decode($string, true);
    }
}

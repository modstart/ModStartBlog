<?php


namespace Module\Vendor\Provider\LBS;


abstract class AbstractIpProvider
{
    abstract public function name();

    abstract public function title();

    
    abstract public function getLocation($ip, $param = []);

}

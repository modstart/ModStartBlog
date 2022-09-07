<?php


namespace Module\Vendor\Provider\PersonVerify;


abstract class AbstractPersonVerifyIdCardProvider
{
    abstract public function name();

    abstract public function title();

    
    abstract public function verify($name, $idCardNumber, $param = []);

}

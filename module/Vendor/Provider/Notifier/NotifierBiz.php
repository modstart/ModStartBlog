<?php


namespace Module\Vendor\Provider\Notifier;


use Module\Vendor\Provider\BizTrait;


class NotifierBiz
{
    use BizTrait;

    public static function registerQuick($name, $title)
    {
        self::register(new QuickNotifierBiz($name, $title));
    }
}

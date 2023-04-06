<?php


namespace Module\Vendor\Provider\Notifier;


abstract class AbstractNotifierProvider
{
    public function name()
    {
        return null;
    }

    public function title()
    {
        return null;
    }

    abstract public function notify($biz, $title, $content, $param = []);
}

<?php


namespace Module\Vendor\Provider\Schedule;



abstract class AbstractScheduleBiz
{
    abstract public function cron();

    public function name()
    {
        return 'default';
    }

    abstract public function title();

    abstract public function run();

    protected function cronEveryMinute()
    {
        return "* * * * *";
    }

    protected function cronEvery10Minute()
    {
        return '*/10 * * * * *';
    }

    protected function cronEvery30Minute()
    {
        return '0,30 * * * * *';
    }

    
    protected function cronEveryDayHour24($hour)
    {
        return "0 $hour * * * *";
    }

    
    protected function cronEveryDayHour24Minute($hour, $minute)
    {
        return "$minute $hour * * * *";
    }

    protected function cronEveryHour()
    {
        return '0 * * * * *';
    }

    protected function cronEveryDay()
    {
        return '0 0 * * * *';
    }
}

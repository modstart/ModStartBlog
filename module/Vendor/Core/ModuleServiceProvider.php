<?php

namespace Module\Vendor\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Module\Vendor\Command\ScheduleRunnerCommand;

class ModuleServiceProvider extends ServiceProvider
{
    
    public function boot(Dispatcher $events)
    {
        $this->commands(ScheduleRunnerCommand::class);
    }

    
    public function register()
    {

    }
}

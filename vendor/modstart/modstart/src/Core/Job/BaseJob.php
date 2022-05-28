<?php

namespace ModStart\Core\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


if (PHP_VERSION_ID >= 80000) {
    abstract class BaseJob implements ShouldQueue
    {
        use Queueable, InteractsWithQueue, SerializesModels;

        
    }
} else {
    abstract class BaseJob implements \Illuminate\Contracts\Bus\SelfHandling, ShouldQueue
    {
        use Queueable, InteractsWithQueue, SerializesModels;

        
    }
}

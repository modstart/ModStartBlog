<?php


namespace Module\Vendor\Shell;


use Illuminate\Console\Application;
use Illuminate\Events\Dispatcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShellApplication extends Application
{
    
    public function __construct($name = 'Console Application', $version = '1.0.0')
    {
        parent::__construct($laravel = new \Illuminate\Foundation\Application(), new Dispatcher($laravel), $version);

        $this->setName($name);
        $this->setAutoExit(true);
        $this->setCatchExceptions(true);
    }

    
    public function command($signature, \Closure $callback, $description = null)
    {
        return $this->add(
            (new ClosureCommand($signature, $callback))->describe($description)
        );
    }

    
    public function runAsSingle(InputInterface $input = null, OutputInterface $output = null)
    {
        foreach ($this->all() as $command) {
            $this->setDefaultCommand($command, true);
            break;
        }

        return $this->run($input, $output);
    }
}

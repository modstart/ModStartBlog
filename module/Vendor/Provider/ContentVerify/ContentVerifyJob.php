<?php


namespace Module\Vendor\Provider\ContentVerify;


use ModStart\Core\Input\Request;
use ModStart\Core\Job\BaseJob;

class ContentVerifyJob extends BaseJob
{
    public $name;
    public $title;
    public $body;
    public $param;

    public static function createQuick($name, $id, $title, $viewUrl)
    {
        self::create($name, [
            'id' => $id,
            'viewUrl' => $viewUrl,
        ], $title);
    }

    
    public static function create($name, $param, $title, $body = null)
    {
        if (!isset($param['domainUrl'])) {
            $param['domainUrl'] = Request::domainUrl();
        }
        if (isset($param['viewUrl'])) {
                        if (!preg_match('/^https?:\/\//', $param['viewUrl'])) {
                $param['viewUrl'] = $param['domainUrl'] . $param['viewUrl'];
            }
        }
        $job = new static();
        $job->name = $name;
        $job->param = $param;
        $job->title = $title;
        $job->body = $body;
        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
    }

    public function handle()
    {
        $provider = ContentVerifyProvider::get($this->name);
        $provider->run($this->param, $this->title, $this->body);
    }
}

<?php


namespace Module\CensorTextTecmz\Core;


use ModStart\Core\Input\Response;
use Module\Vendor\Provider\CensorText\AbstractCensorTextProvider;
use Module\Vendor\Tecmz\Tecmz;

class CensorTextTecmzProvider extends AbstractCensorTextProvider
{
    public function name()
    {
        return 'tecmz';
    }

    public function title()
    {
        return '魔众文本审核';
    }

    public function verify($content, $param = [])
    {
        $instance = Tecmz::instance(
            modstart_config('CensorTextTecmz_AppId'),
            modstart_config('CensorTextTecmz_AppSecret')
        );
        $ret = $instance->censorText($content);
//        print_r([
//            $content,
//            $ret
//        ]);
        if (Response::isError($ret)) {
            return Response::generateSuccessData([
                'pass' => false,
            ]);
        }
        if($ret['data']['result']=='合规'){
            return Response::generateSuccessData([
                'pass' => true,
            ]);
        }
        return Response::generateSuccessData([
            'pass' => false,
        ]);
    }

}
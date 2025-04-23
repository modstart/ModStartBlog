<?php


namespace Module\CensorImageTecmz\Core;


use ModStart\Core\Input\Response;
use Module\Vendor\Provider\CensorImage\AbstractCensorImageProvider;
use Module\Vendor\Tecmz\Tecmz;

class CensorImageTecmzProvider extends AbstractCensorImageProvider
{
    public function name()
    {
        return 'tecmz';
    }

    public function title()
    {
        return '图片智能审核';
    }

    public function verify($content, $param = [])
    {
        $instance = Tecmz::instance(
            modstart_config('CensorImageTecmz_AppId'),
            modstart_config('CensorImageTecmz_AppSecret')
        );
        $ret = $instance->censorImage(null, $content);
//        print_r([
//            $content,
//            $ret
//        ]);
        if (Response::isError($ret)) {
            return Response::generateSuccessData([
                'pass' => false,
            ]);
        }
        if ($ret['data']['result'] == '合规') {
            return Response::generateSuccessData([
                'pass' => true,
            ]);
        }
        return Response::generateSuccessData([
            'pass' => false,
        ]);
    }

}

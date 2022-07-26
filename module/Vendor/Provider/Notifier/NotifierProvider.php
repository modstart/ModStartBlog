<?php


namespace Module\Vendor\Provider\Notifier;


use Module\Vendor\Util\NoneLoginOperateUtil;

class NotifierProvider
{
    
    public static function all()
    {
        static $instances = null;
        if (null === $instances) {
            $drivers = config('NotifierProviders');
            if (empty($drivers)) {
                $drivers = [
                    DefaultNotifierProvider::class
                ];
            }
            $instances = array_map(function ($driver) {
                return app($driver);
            }, array_unique($drivers));
        }
        return $instances;
    }

    
    public static function notify($biz, $title, $content, $param = [])
    {
        foreach (self::all() as $instance) {
            $instance->notify($biz, $title, $content, $param);
        }
    }

    public static function notifyProcess($biz, $title, $content, $processUrl, $processUrlParam = [])
    {
        self::notify($biz, $title, $content, array_merge($processUrlParam, [
            'processUrl' => $processUrl,
        ]));
    }

    public static function notifyNoneLoginOperateProcessUrl($biz, $title, $content, $processUrlPath, $processUrlParam = [])
    {
        $viewUrl = null;
        if (isset($processUrlParam['viewUrl'])) {
            $viewUrl = $processUrlParam['viewUrl'];
            unset($processUrlParam['viewUrl']);
        }
        $processUrl = NoneLoginOperateUtil::generateUrl($processUrlPath, $processUrlParam);
        self::notifyProcess($biz, $title, $content, $processUrl, array_merge($processUrlParam, [
            'viewUrl' => $viewUrl,
        ]));
    }
}

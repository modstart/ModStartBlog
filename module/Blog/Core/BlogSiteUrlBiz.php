<?php


namespace Module\Blog\Core;


use ModStart\Core\Dao\ModelUtil;
use Module\Vendor\Provider\SiteUrl\AbstractSiteUrlBiz;

class BlogSiteUrlBiz extends AbstractSiteUrlBiz
{
    const NAME = 'blog';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return '博客';
    }

    public function urlBuildBatch($nextId, $param = [])
    {
        $records = [];
        $batchRet = ModelUtil::batch('blog', $nextId);
        $finish = empty($batchRet['records']);
        foreach ($batchRet['records'] as $record) {
            $records[] = [
                'url' => modstart_web_url('blog/' . $record['id']),
                'updateTime' => $record['updated_at'],
            ];
        }
        return [
            'finish' => $finish,
            'records' => $records,
            'nextId' => $batchRet['nextId'],
        ];
    }

}

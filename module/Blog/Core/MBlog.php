<?php

use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\HtmlUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Util\BlogCategoryUtil;
use Module\Blog\Util\BlogTagUtil;


class MBlog
{
    public static function categoryTree()
    {
        return BlogCategoryUtil::categoryTree();
    }

    
    public static function latestBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit);
        return $paginateData['records'];
    }


    
    public static function hottestBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'order' => ['clickCount', 'desc'],
        ]);
        return $paginateData['records'];
    }

    
    public static function paginateBlog($categoryId, $page = 1, $pageSize = 10, $option = [])
    {
        if ($categoryId > 0) {
            $option['whereIn'][] = ['categoryId', BlogCategoryUtil::childrenIds($categoryId)];
        }
        $option['where']['isPublished'] = true;
        if (!isset($option['order'])) {
            $option['order'] = ['postTime', 'desc'];
        }
        if (!isset($option['whereOperate'])) {
            $option['whereOperate'] = [];
        }
        $option['whereOperate'] = array_merge([
            ['postTime', '<', date('Y-m-d H:i:s')],
        ], $option['whereOperate']);

        $paginateData = ModelUtil::paginate('blog', $page, $pageSize, $option);
        $records = $paginateData['records'];
        ModelUtil::decodeRecordsJson($records, 'images');
        TagUtil::recordsString2Array($records, 'tag');
        foreach ($records as $i => $v) {
            $records[$i]['_category'] = BlogCategoryUtil::get($v['categoryId']);
            $records[$i]['images'] = AssetsUtil::fixFull($v['images']);
            $records[$i]['_cover'] = null;
            if (isset($records[$i]['images'][0])) {
                $records[$i]['_cover'] = $records[$i]['images'][0];
            }
            if (empty($records[$i]['_cover'])) {
                $ret = HtmlUtil::extractTextAndImages($v['content']);
                if (isset($ret['images'][0])) {
                    $records[$i]['_cover'] = AssetsUtil::fixFull($ret['images'][0]);
                }
            }
        }
        return [
            'records' => $records,
            'total' => $paginateData['total'],
        ];
    }

    
    public static function getCategory($categoryId)
    {
        return BlogCategoryUtil::get($categoryId);
    }

    
    public static function tags()
    {
        return BlogTagUtil::all();
    }

    
    public static function tagRecords()
    {
        return BlogTagUtil::records();
    }
}

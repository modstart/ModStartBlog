<?php

use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\HtmlUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Model\Blog;
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

    
    public static function topestBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'isTop' => true,
        ]);
        return $paginateData['records'];
    }

    
    public static function hotBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'isHot' => true,
        ]);
        return $paginateData['records'];
    }

    
    public static function recommendBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'isRecommend' => true,
        ]);
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
            $option['order'] = [
                ['isTop', 'desc'],
                ['postTime', 'desc'],
            ];
        }
        if (!isset($option['whereOperate'])) {
            $option['whereOperate'] = [];
        }
        $option['whereOperate'] = array_merge([
            ['postTime', '<', date('Y-m-d H:i:s')],
        ], $option['whereOperate']);

        $paginateData = ModelUtil::paginate('blog', $page, $pageSize, $option);
        $records = self::buildRecords($paginateData['records']);
        return [
            'records' => $records,
            'total' => $paginateData['total'],
        ];
    }

    public static function buildRecords($records)
    {
        ModelUtil::decodeRecordsJson($records, 'images');
        TagUtil::recordsString2Array($records, 'tag');
        foreach ($records as $i => $v) {
            $records[$i]['_category'] = BlogCategoryUtil::get($v['categoryId']);
            $records[$i]['images'] = AssetsUtil::fixFull($v['images']);
            $records[$i]['_images'] = [];
            $records[$i]['_images'] = array_merge($records[$i]['_images'], $records[$i]['images']);
            $records[$i]['_cover'] = null;
            if (isset($records[$i]['images'][0])) {
                $records[$i]['_cover'] = $records[$i]['images'][0];
            }
            if (isset($v['content'])) {
                $ret = HtmlUtil::extractTextAndImages($v['content']);
                if (!empty($ret['images'])) {
                    $ret['images'] = AssetsUtil::fixFull($ret['images']);
                    $records[$i]['_images'] = array_merge($records[$i]['_images'], $ret['images']);
                }
                if (empty($records[$i]['_cover']) && isset($ret['images'][0])) {
                    $records[$i]['_cover'] = $ret['images'][0];
                }
            }
        }
        return $records;
    }

    
    public static function listBlogByYear($option = [])
    {

        $records = Blog::query()->where(['isPublished' => true])
            ->where('postTime', '<', date('Y-m-d H:i:s'))
            ->orderBy('postTime', 'desc')
            ->get(['id', 'images', 'tag', 'title', 'categoryId', 'postTime'])
            ->toArray();
        $records = self::buildRecords($records);

        $yearRecords = [];
        foreach ($records as $i => $v) {
            $year = date('Y', strtotime($v['postTime']));
            if (!isset($yearRecords[$year])) {
                $yearRecords[$year] = [
                    'count' => 0,
                    'year' => $year,
                    'records' => [],
                ];
            }
            $yearRecords[$year]['records'][] = $v;
        }
        foreach ($yearRecords as $i => $v) {
            $yearRecords[$i]['count'] = count($v['records']);
        }

        return [
            'total' => count($records),
            'records' => $yearRecords,
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

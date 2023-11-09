<?php


namespace Module\Blog\Util;


use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\HtmlUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Core\BlogMemberFavBiz;
use Module\Blog\Core\BlogMemberLikeBiz;
use Module\Blog\Type\BlogVisitMode;
use Module\MemberFav\Model\MemberFav;
use Module\MemberLike\Model\MemberLike;

class BlogUtil
{
    public static function updateFavoriteCount($id)
    {
        if (modstart_module_enabled('MemberFav')) {
            ModelUtil::update('blog', $id, [
                'favCount' => MemberFav::countBiz(BlogMemberFavBiz::NAME, $id),
            ]);
        }
    }

    public static function updateLikeCount($id)
    {
        if (modstart_module_enabled('MemberLike')) {
            ModelUtil::update('blog', $id, [
                'likeCount' => MemberLike::countBiz(BlogMemberLikeBiz::NAME, $id),
            ]);
        }
    }

    public static function buildRecord($record)
    {
        $record['_category'] = BlogCategoryUtil::get($record['categoryId']);
        $record['images'] = AssetsUtil::fixFull($record['images']);
        $summary = $record['seoDescription'];
        $images = $record['images'];
        if (isset($record['content'])) {
            $ret = HtmlUtil::extractTextAndImages($record['content']);
            if (!empty($ret['images'])) {
                $images = array_merge($images, $ret['images']);
            }
            if (empty($summary) && !empty($ret['text'])) {
                $summary = $ret['text'];
            }
        }
        $record['_images'] = AssetsUtil::fixFull($images);
        $record['_summary'] = $summary;
        $cover = null;
        if (empty($cover) && isset($record['images'][0])) {
            $cover = $record['images'][0];
        }
        $record['_cover'] = AssetsUtil::fixFull($cover);
        $record['_date'] = date('Y-m-d', strtotime($record['postTime']));

        $record['_url'] = UrlUtil::blog($record);
        switch ($record['visitMode']) {
            case BlogVisitMode::PASSWORD:
                $record['content'] = null;
                break;
            case BlogVisitMode::OPEN:
            default:
                // do nothing
                break;
        }
        return $record;
    }

    public static function buildRecords($records)
    {
        ModelUtil::decodeRecordsJson($records, 'images');
        TagUtil::recordsString2Array($records, 'tag');
        foreach ($records as $i => $v) {
            $records[$i] = self::buildRecord($v);
        }
        return $records;
    }

    public static function paginateBlogsByCategoryId($categoryId, $page = 1, $pageSize = 10, $option = [])
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
}

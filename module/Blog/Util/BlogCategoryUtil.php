<?php


namespace Module\Blog\Util;


use Illuminate\Support\Facades\Cache;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\TreeUtil;

class BlogCategoryUtil
{
    public static function clearCache()
    {
        Cache::forget('BlogCategories');
    }

    public static function all()
    {
        return Cache::rememberForever('BlogCategories', function () {
            $records = ModelUtil::all('blog_category', [], ['*'], ['sort', 'desc']);
            AssetsUtil::recordsFixFullOrDefault($records, 'cover', 'asset/image/none.png');
            foreach ($records as $k => $v) {
                $records[$k]['_url'] = UrlUtil::category($v);
            }
            return $records;
        });
    }

    public static function categoryTree()
    {
        $nodes = self::all();
        return TreeUtil::nodesToTree($nodes);
    }

    public static function get($id)
    {
        foreach (self::all() as $one) {
            if ($one['id'] == $id) {
                return $one;
            }
        }
        return null;
    }

    public static function childrenIds($categoryId)
    {
        if ($categoryId <= 0) {
            return [];
        }
        $nodes = self::all();
        return array_merge([$categoryId], TreeUtil::nodesChildrenIds($nodes, $categoryId));
    }

    public static function updateCount($categoryIds)
    {
        if (!is_array($categoryIds)) {
            $categoryIds = [$categoryIds];
        }
        $categoryIds = array_unique($categoryIds);
        foreach ($categoryIds as $catId) {
            $chapter = self::get($catId);
            if (empty($chapter)) {
                continue;
            }
            $tree = self::categoryTree();
            $chain = TreeUtil::treeChain($tree, $catId);
            foreach ($chain as $item) {
                $ids = TreeUtil::treeNodeChildrenIds($tree, $item['id']);
                if (empty($ids)) {
                    $blogCount = 0;
                } else {
                    $blogCount = ModelUtil::model('blog')->whereIn('categoryId', $ids)->count();
                }
                ModelUtil::update('blog_category', $item['id'], [
                    'blogCount' => $blogCount,
                ]);
            }
        }
        self::clearCache();
    }
}

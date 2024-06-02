<?php


namespace Module\Blog\Util;


use Illuminate\Support\Facades\Cache;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\TreeUtil;
use Module\Blog\Model\Blog;
use Module\Blog\Model\BlogCategory;

class BlogCategoryUtil
{
    public static function clearCache()
    {
        Cache::forget('Blog:Categories');
    }

    public static function all()
    {
        return Cache::rememberForever('Blog:Categories', function () {
            $records = ModelUtil::all(BlogCategory::class, [], ['*'], ['sort', 'asc']);
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

    public static function categoryTreeFlat()
    {
        $tree = self::categoryTree();
        $nodes = TreeUtil::treeToListWithLevel($tree);
        foreach ($nodes as $i => $v) {
            $chain = TreeUtil::nodesChain($nodes, $v['id']);
            $nodes[$i]['_fullTitle'] = join('-', array_map(function ($item) {
                return $item['title'];
            }, $chain));
        }
        return $nodes;
    }

    public static function categoryChainWithItems($categoryId)
    {
        $records = self::all();
        return TreeUtil::nodesChainWithItems($records, $categoryId);
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

    public static function listChildCategories($categoryId)
    {
        $records = self::all();
        $records = array_filter($records, function ($item) use ($categoryId) {
            return $item['pid'] == $categoryId;
        });
        return array_values($records);
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
                    $blogCount = Blog::published()->whereIn('categoryId', $ids)->count();
                }
                ModelUtil::update(BlogCategory::class, $item['id'], [
                    'blogCount' => $blogCount,
                ]);
            }
        }
        self::clearCache();
    }
}

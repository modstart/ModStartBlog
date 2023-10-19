<?php

use Illuminate\Support\Facades\DB;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\HtmlUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Model\Blog;
use Module\Blog\Type\BlogVisitMode;
use Module\Blog\Util\BlogCategoryUtil;
use Module\Blog\Util\BlogTagUtil;

/**
 * @Util 博客操作
 */
class MBlog
{
    public static function categoryTree()
    {
        return BlogCategoryUtil::categoryTree();
    }

    /**
     * @Util 获取最新博客
     * @param $limit int 限制条数
     * @return array 数组
     * @returnExample
     * [
     *   {
     *     "id": 19, // 博客ID
     *     "created_at": "2017-12-20 16:35:24", // 创建时间
     *     "updated_at": "2022-09-21 14:59:09", // 更新时间
     *     "title": "博客标题",
     *     "tag": [
     *       "标签1",
     *       "标签2"
     *     ],
     *     "summary": "博客摘要",
     *     "images": [
     *       "https://example.com/xxx.jpg" // 博客图片多张
     *     ],
     *     "content": "内容HTML",
     *     "isPublished": 1, // 是否发布
     *     "postTime": "2017-12-20 16:34:44", // 发布时间
     *     "clickCount": 215048, // 点击量
     *     "seoKeywords": "SEO关键词",
     *     "seoDescription": "SEO描述内容",
     *     "isTop": null, // 是否置顶
     *     "commentCount": 16, // 评论数
     *     "categoryId": 1, // 分类ID
     *     "_category": {
     *       "id": 1, // 分类ID
     *       "created_at": "2022-05-27 14:51:09", // 分类创建时间
     *       "updated_at": "2022-09-21 14:55:33", // 分类更新时间
     *       "pid": 0, // 父分类ID
     *       "sort": 1, // 分类排序
     *       "title": "分类名称",
     *       "blogCount": 1, // 分类下博客数量
     *       "cover": "https://xx.com/xx.jpg",
     *       "keywords": "分类关键词",
     *       "description": "分类描述"
     *     },
     *     "_cover": "https://xx.com/xx.jpg" // 博客封面，如果博客图片为空，会尝试自动从内容中抽取
     *   },
     *   // ...
     * ]
     */
    public static function latestBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit);
        return $paginateData['records'];
    }

    /**
     * @Util 获取置顶博客
     * @param $limit int 限制条数
     * @return array 数组
     * @returnExample
     * [
     *   {
     *     "id": 19, // 博客ID
     *     "created_at": "2017-12-20 16:35:24", // 创建时间
     *     "updated_at": "2022-09-21 14:59:09", // 更新时间
     *     "title": "博客标题",
     *     "tag": [
     *       "标签1",
     *       "标签2"
     *     ],
     *     "summary": "博客摘要",
     *     "images": [
     *       "https://example.com/xxx.jpg" // 博客图片多张
     *     ],
     *     "content": "内容HTML",
     *     "isPublished": 1, // 是否发布
     *     "postTime": "2017-12-20 16:34:44", // 发布时间
     *     "clickCount": 215048, // 点击量
     *     "seoKeywords": "SEO关键词",
     *     "seoDescription": "SEO描述内容",
     *     "isTop": null, // 是否置顶
     *     "commentCount": 16, // 评论数
     *     "categoryId": 1, // 分类ID
     *     "_category": {
     *       "id": 1, // 分类ID
     *       "created_at": "2022-05-27 14:51:09", // 分类创建时间
     *       "updated_at": "2022-09-21 14:55:33", // 分类更新时间
     *       "pid": 0, // 父分类ID
     *       "sort": 1, // 分类排序
     *       "title": "分类名称",
     *       "blogCount": 1, // 分类下博客数量
     *       "cover": "https://xx.com/xx.jpg",
     *       "keywords": "分类关键词",
     *       "description": "分类描述"
     *     },
     *     "_cover": "https://xx.com/xx.jpg" // 博客封面，如果博客图片为空，会尝试自动从内容中抽取
     *   },
     *   // ...
     * ]
     */
    public static function topestBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'isTop' => true,
        ]);
        return $paginateData['records'];
    }

    /**
     * @Util 获取置顶博客
     * @param $limit int 限制条数
     * @return array 数组
     * @returnExample
     * [
     *   {
     *     "id": 19, // 博客ID
     *     "created_at": "2017-12-20 16:35:24", // 创建时间
     *     "updated_at": "2022-09-21 14:59:09", // 更新时间
     *     "title": "博客标题",
     *     "tag": [
     *       "标签1",
     *       "标签2"
     *     ],
     *     "summary": "博客摘要",
     *     "images": [
     *       "https://example.com/xxx.jpg" // 博客图片多张
     *     ],
     *     "content": "内容HTML",
     *     "isPublished": 1, // 是否发布
     *     "postTime": "2017-12-20 16:34:44", // 发布时间
     *     "clickCount": 215048, // 点击量
     *     "seoKeywords": "SEO关键词",
     *     "seoDescription": "SEO描述内容",
     *     "isTop": null, // 是否置顶
     *     "commentCount": 16, // 评论数
     *     "categoryId": 1, // 分类ID
     *     "_category": {
     *       "id": 1, // 分类ID
     *       "created_at": "2022-05-27 14:51:09", // 分类创建时间
     *       "updated_at": "2022-09-21 14:55:33", // 分类更新时间
     *       "pid": 0, // 父分类ID
     *       "sort": 1, // 分类排序
     *       "title": "分类名称",
     *       "blogCount": 1, // 分类下博客数量
     *       "cover": "https://xx.com/xx.jpg",
     *       "keywords": "分类关键词",
     *       "description": "分类描述"
     *     },
     *     "_cover": "https://xx.com/xx.jpg" // 博客封面，如果博客图片为空，会尝试自动从内容中抽取
     *   },
     *   // ...
     * ]
     */
    public static function hotBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'isHot' => true,
        ]);
        return $paginateData['records'];
    }

    /**
     * @Util 获取置顶博客
     * @param $limit int 限制条数
     * @return array 数组
     * @returnExample
     * [
     *   {
     *     "id": 19, // 博客ID
     *     "created_at": "2017-12-20 16:35:24", // 创建时间
     *     "updated_at": "2022-09-21 14:59:09", // 更新时间
     *     "title": "博客标题",
     *     "tag": [
     *       "标签1",
     *       "标签2"
     *     ],
     *     "summary": "博客摘要",
     *     "images": [
     *       "https://example.com/xxx.jpg" // 博客图片多张
     *     ],
     *     "content": "内容HTML",
     *     "isPublished": 1, // 是否发布
     *     "postTime": "2017-12-20 16:34:44", // 发布时间
     *     "clickCount": 215048, // 点击量
     *     "seoKeywords": "SEO关键词",
     *     "seoDescription": "SEO描述内容",
     *     "isTop": null, // 是否置顶
     *     "commentCount": 16, // 评论数
     *     "categoryId": 1, // 分类ID
     *     "_category": {
     *       "id": 1, // 分类ID
     *       "created_at": "2022-05-27 14:51:09", // 分类创建时间
     *       "updated_at": "2022-09-21 14:55:33", // 分类更新时间
     *       "pid": 0, // 父分类ID
     *       "sort": 1, // 分类排序
     *       "title": "分类名称",
     *       "blogCount": 1, // 分类下博客数量
     *       "cover": "https://xx.com/xx.jpg",
     *       "keywords": "分类关键词",
     *       "description": "分类描述"
     *     },
     *     "_cover": "https://xx.com/xx.jpg" // 博客封面，如果博客图片为空，会尝试自动从内容中抽取
     *   },
     *   // ...
     * ]
     */
    public static function recommendBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'isRecommend' => true,
        ]);
        return $paginateData['records'];
    }


    /**
     * @Util 获取最热博客
     * @param $limit int 限制条数
     * @return array 数组
     * @returnExample
     * [
     *   {
     *     "id": 19, // 博客ID
     *     "created_at": "2017-12-20 16:35:24", // 创建时间
     *     "updated_at": "2022-09-21 14:59:09", // 更新时间
     *     "title": "博客标题",
     *     "tag": [
     *       "标签1",
     *       "标签2"
     *     ],
     *     "summary": "博客摘要",
     *     "images": [
     *       "https://example.com/xxx.jpg" // 博客图片多张
     *     ],
     *     "content": "内容HTML",
     *     "isPublished": 1, // 是否发布
     *     "postTime": "2017-12-20 16:34:44", // 发布时间
     *     "clickCount": 215048, // 点击量
     *     "seoKeywords": "SEO关键词",
     *     "seoDescription": "SEO描述内容",
     *     "isTop": null, // 是否置顶
     *     "commentCount": 16, // 评论数
     *     "categoryId": 1, // 分类ID
     *     "_category": {
     *       "id": 1, // 分类ID
     *       "created_at": "2022-05-27 14:51:09", // 分类创建时间
     *       "updated_at": "2022-09-21 14:55:33", // 分类更新时间
     *       "pid": 0, // 父分类ID
     *       "sort": 1, // 分类排序
     *       "title": "分类名称",
     *       "blogCount": 1, // 分类下博客数量
     *       "cover": "https://xx.com/xx.jpg",
     *       "keywords": "分类关键词",
     *       "description": "分类描述"
     *     },
     *     "_cover": "https://xx.com/xx.jpg" // 博客封面，如果博客图片为空，会尝试自动从内容中抽取
     *   },
     *   // ...
     * ]
     */
    public static function hottestBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'order' => ['clickCount', 'desc'],
        ]);
        return $paginateData['records'];
    }

    /**
     * @Util 随机获取博客
     * @param $limit int 限制条数
     * @return array 数组
     * @returnExample
     * [
     *   {
     *     "id": 19, // 博客ID
     *     "created_at": "2017-12-20 16:35:24", // 创建时间
     *     "updated_at": "2022-09-21 14:59:09", // 更新时间
     *     "title": "博客标题",
     *     "tag": [
     *       "标签1",
     *       "标签2"
     *     ],
     *     "summary": "博客摘要",
     *     "images": [
     *       "https://example.com/xxx.jpg" // 博客图片多张
     *     ],
     *     "content": "内容HTML",
     *     "isPublished": 1, // 是否发布
     *     "postTime": "2017-12-20 16:34:44", // 发布时间
     *     "clickCount": 215048, // 点击量
     *     "seoKeywords": "SEO关键词",
     *     "seoDescription": "SEO描述内容",
     *     "isTop": null, // 是否置顶
     *     "commentCount": 16, // 评论数
     *     "categoryId": 1, // 分类ID
     *     "_category": {
     *       "id": 1, // 分类ID
     *       "created_at": "2022-05-27 14:51:09", // 分类创建时间
     *       "updated_at": "2022-09-21 14:55:33", // 分类更新时间
     *       "pid": 0, // 父分类ID
     *       "sort": 1, // 分类排序
     *       "title": "分类名称",
     *       "blogCount": 1, // 分类下博客数量
     *       "cover": "https://xx.com/xx.jpg",
     *       "keywords": "分类关键词",
     *       "description": "分类描述"
     *     },
     *     "_cover": "https://xx.com/xx.jpg" // 博客封面，如果博客图片为空，会尝试自动从内容中抽取
     *   },
     *   // ...
     * ]
     */
    public static function randomBlog($limit)
    {
        $paginateData = self::paginateBlog(0, 1, $limit, [
            'order' => [\Illuminate\Support\Facades\DB::raw('RAND()'), ''],
        ]);
        return $paginateData['records'];
    }

    /**
     * @Util 获取博客分页
     * @param $categoryId int 分类ID
     * @param $page int 分页，默认为1
     * @param $pageSize int 分页大小，默认为10
     * @param $option array 分页高级参数
     * @return array 数组
     * @returnExample
     * {
     *   "records": [
     *     {
     *       "id": 19,
     *       "created_at": "2017-12-20 16:35:24",
     *       "updated_at": "2022-09-21 14:59:09",
     *       "title": "博客标题",
     *       "tag": [
     *         "标签1",
     *         "标签2"
     *       ],
     *       "summary": "博客摘要",
     *       "images": [
     *         "https://example.com/xxx.jpg"
     *       ],
     *       "content": "内容HTML",
     *       "isPublished": 1,
     *       "postTime": "2017-12-20 16:34:44",
     *       "clickCount": 215048,
     *       "seoKeywords": "博客SEO关键词",
     *       "seoDescription": "博客SEO描述",
     *       "isTop": null,
     *       "commentCount": 16,
     *       "categoryId": 1,
     *       "_category": {
     *         "id": 1,
     *         "created_at": "2022-05-27 14:51:09",
     *         "updated_at": "2022-09-21 14:55:33",
     *         "pid": 0,
     *         "sort": 1,
     *         "title": "分类名称",
     *         "blogCount": 1,
     *         "cover": "https://xx.com/xx.jpg",
     *         "keywords": "分类关键词",
     *         "description": "分类描述"
     *       },
     *       "_cover": "https://xx.com/xx.jpg"
     *     },
     *     // ...
     *   ],
     *   "total": 1
     * }
     * @example
     * // $option 说明
     * // 发布时间倒序
     * $option = [ 'order'=>['postTime', 'desc'] ];
     * // 发布时间顺序
     * $option = [ 'order'=>['postTime', 'asc'] ];
     * // 增加检索条件
     * $option = [ 'where'=>['id'=>1] ];
     */
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
            switch ($v['visitMode']) {
                case BlogVisitMode::PASSWORD:
                    $records[$i]['content'] = null;
                    break;
                case BlogVisitMode::OPEN:
                default:
                    // do nothing
                    break;
            }
        }
        return $records;
    }

    /**
     * @Util 根据年份列出所有博客
     * @param array $option
     * @return array
     * {
     *   "records": [
     *     {
     *       "id": 19,
     *       "created_at": "2017-12-20 16:35:24",
     *       "updated_at": "2022-09-21 14:59:09",
     *       "title": "博客标题",
     *       "tag": [
     *         "标签1",
     *         "标签2"
     *       ],
     *       "summary": "博客摘要",
     *       "images": [
     *         "https://example.com/xxx.jpg"
     *       ],
     *       "content": "内容HTML",
     *       "isPublished": 1,
     *       "postTime": "2017-12-20 16:34:44",
     *       "clickCount": 215048,
     *       "seoKeywords": "博客SEO关键词",
     *       "seoDescription": "博客SEO描述",
     *       "isTop": null,
     *       "commentCount": 16,
     *       "categoryId": 1,
     *       "_category": {
     *         "id": 1,
     *         "created_at": "2022-05-27 14:51:09",
     *         "updated_at": "2022-09-21 14:55:33",
     *         "pid": 0,
     *         "sort": 1,
     *         "title": "分类名称",
     *         "blogCount": 1,
     *         "cover": "https://xx.com/xx.jpg",
     *         "keywords": "分类关键词",
     *         "description": "分类描述"
     *       },
     *       "_cover": "https://xx.com/xx.jpg"
     *     },
     *     // ...
     *   ],
     *   "total": 1
     * }
     */
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

    /**
     * @Util 获取分类信息
     * @param $categoryId int 分类ID
     * @return array 数组
     * @returnExample
     * {
     *   "id": 1, // 分类ID
     *   "created_at": "2022-05-27 14:51:09",
     *   "updated_at": "2022-09-21 14:55:33",
     *   "pid": 0,
     *   "sort": 1,
     *   "title": "分类名称",
     *   "blogCount": 1,
     *   "cover": "https://example.com/xxx.jpg",
     *   "keywords": "分类关键词",
     *   "description": "分类描述"
     * }
     */
    public static function getCategory($categoryId)
    {
        return BlogCategoryUtil::get($categoryId);
    }

    /**
     * @Util 获取所有博客标签信息
     * @param $limit int 限制数量，0为不限制
     * @return array 数组，标签→数量映射
     * @returnExample
     * {
     *   "标签1": 1,
     *   "标签2": 2
     * }
     */
    public static function tags($limit = 0)
    {
        $records = BlogTagUtil::all();
        if ($limit > 0) {
            $records = array_slice($records, 0, $limit);
        }
        return $records;
    }

    /**
     * @Util 获取所有博客标签信息
     * @return array 数组
     * @returnExample
     * [
     *  {
     *   "name": "标签1",
     *   "count": 1
     *  }
     * ]
     */
    public static function tagRecords()
    {
        return BlogTagUtil::records();
    }

    /**
     * @Util 按月获取归档数量
     * @param $year number|string 年
     * @return array 数组
     * @returnExample
     * [
     * {
     * "month": "06",
     * "total": 1
     * },
     * {
     * "month": "12",
     * "total": 6
     * }
     * ]
     */
    public static function archiveMonthCounts($year)
    {
        $archiveCounts = Blog::query()
            ->where('postTime', '<', date('Y-m-d H:i:s'))
            ->where('postTime', '>=', $year . '-01-01 00:00:00')
            ->where('postTime', '<=', $year . '-12-31 23:59:59')
            ->select([DB::raw("DATE_FORMAT(`postTime`,'%m') AS `month`"), DB::raw("COUNT(*) AS total")])
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get()->toArray();
        return $archiveCounts;
    }

    /**
     * @Util 按年获取归档数量
     * @return array 数组
     * @returnExample
     * [
     * {
     * "year": "06",
     * "total": 1
     * },
     * {
     * "year": "12",
     * "total": 6
     * }
     * ]
     */
    public static function archiveYearCounts()
    {
        $archiveCounts = Blog::query()
            ->where('postTime', '<', date('Y-m-d H:i:s'))
            ->select([DB::raw("DATE_FORMAT(`postTime`,'%Y') AS `year`"), DB::raw("COUNT(*) AS total")])
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get()->toArray();
        return $archiveCounts;
    }
}

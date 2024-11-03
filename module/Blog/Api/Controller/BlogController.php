<?php


namespace Module\Blog\Api\Controller;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Core\Util\HtmlUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Core\BlogSuperSearchBiz;
use Module\Blog\Model\Blog;
use Module\Blog\Type\BlogCommentStatus;
use Module\Blog\Type\BlogVisitMode;
use Module\Blog\Util\BlogCategoryUtil;
use Module\Blog\Util\UrlUtil;
use Module\HotSearch\Util\HotSearchUtil;
use Module\Member\Util\MemberUtil;

/**
 * @Api 博客系统
 */
class BlogController extends Controller
{
    /**
     * @Api 博客-列表
     * @ApiBodyParam page int 页码
     * @ApiBodyParam categoryId int 分类ID
     * @ApiBodyParam keyword string 关键字
     */
    public function paginate()
    {
        $input = InputPackage::buildFromInput();
        $page = $input->getPage();
        $pageSize = 10;
        $categoryId = $input->getInteger('categoryId');
        $keywords = $input->getTrimString('keywords');
        $option = [
            'search' => [],
        ];
        $pageTitle = '';
        $pageKeywords = modstart_config('siteKeywords');
        $pageDescription = modstart_config('siteDescription');

        $provider = BlogSuperSearchBiz::provider();
        $markKeywords = [];
        if (empty($provider)) {
            if ($keywords) {
                $option['search'][] = ['__exp' => 'or', 'title' => ['like' => "%$keywords%"], 'tag' => ['like' => "%:$keywords:%"]];
                $pageTitle = $keywords;
                $pageKeywords = $keywords;
                $pageDescription = $keywords;
            }
            $paginateData = \MBlog::paginateBlog($categoryId, $page, $pageSize, $option);
            $markKeywords = mb_str_split($keywords);
        } else {
            $query = [
                ['isPublished' => ['eq' => 1]],
            ];
            $order = [
                ['isTop', 'desc'],
                ['id', 'desc'],
            ];
            if ($categoryId) {
                $query[] = ['categoryId' => ['eq' => $categoryId]];
            }
            if ($keywords) {
                $query['_or'] = [
                    ['title' => ['like' => $keywords]],
                    ['summary' => ['like' => $keywords]],
                    ['content' => ['like' => $keywords]],
                    ['keywords' => ['in' => [$keywords]]],
                ];
                $query['highlights'] = [
                    'fields' => [
                        'title',
                        'summary',
                        'content',
                        'keywords',
                    ]
                ];
            }
            $paginateData = $provider->search('blog', $page, $pageSize, $query, $order);
            $markKeywords = $paginateData['markKeywords'];
            $itemIds = array_map(function ($q) {
                return $q['id'];
            }, $paginateData['records']);
            $paginateData['records'] = ModelUtil::allInWithOrder('blog', 'id', $itemIds);
            $paginateData['records'] = \MBlog::buildRecords($paginateData['records']);
            // Log::info('records-' . json_encode($paginateData['records']));
        }

        if ($keywords) {
            if (modstart_module_enabled('HotSearch')) {
                HotSearchUtil::hit($keywords);
            }
        }

        $category = null;
        $childCategories = [];
        $categoryChain = BlogCategoryUtil::categoryChainWithItems($categoryId);
        if ($categoryId > 0) {
            $category = \MBlog::getCategory($categoryId);
            $childCategories = \MBlog::listChildCategories($categoryId);
            BizException::throwsIfEmpty('分类不存在', $category);
            $pageTitle = $category['title'];
            $pageKeywords = $category['keywords'];
            $pageDescription = $category['description'];
        }
        return Response::generateSuccessData([
            'pageTitle' => $pageTitle,
            'pageKeywords' => $pageKeywords,
            'pageDescription' => $pageDescription,
            'page' => $page,
            'pageSize' => $pageSize,
            'category' => $category,
            'childCategories' => $childCategories,
            'categoryChain' => $categoryChain,
            'keywords' => $keywords,
            'markKeywords' => $markKeywords,
            'records' => $paginateData['records'],
            'total' => $paginateData['total'],
        ]);
    }

    /**
     * @Api 博客-详情
     * @ApiBodyParam id int 博客ID
     */
    public function get()
    {
        $input = InputPackage::buildFromInput();
        $id = $input->getInteger('id');
        $record = Blog::published()->where(['id' => $id])->first();
        BizException::throwsIfEmpty('记录不存在', $record);
        $record = $record->toArray();
        ModelUtil::decodeRecordJson($record, ['images']);
        $record['images'] = AssetsUtil::fixFull($record['images']);
        $record['tag'] = TagUtil::string2Array($record['tag']);
        $record['_category'] = BlogCategoryUtil::get($record['categoryId']);
        $record['_date'] = date('Y-m-d', strtotime($record['created_at']));
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

        $record['_visitVerified'] = false;
        switch ($record['visitMode']) {
            case BlogVisitMode::PASSWORD:
                $visitVerifiedIds = Session::get('Blog_VisitVerifiedIds');
                if (empty($visitVerifiedIds)) {
                    $visitVerifiedIds = [];
                }
                if (in_array($record['id'], $visitVerifiedIds)) {
                    $record['_visitVerified'] = true;
                } else {
                    $record['content'] = null;
                }
                break;
            case BlogVisitMode::OPEN:
            default:
                $record['_visitVerified'] = true;
                break;
        }

        $comments = [];
        $commentTotal = 0;

        $commentPage = $input->getInteger('commentPage', 1);
        $commentPageSize = 10;
        if ($record['_visitVerified']) {
            $option = [];
            $option['where']['blogId'] = $record['id'];
            $option['where']['status'] = BlogCommentStatus::VERIFY_SUCCESS;
            $option['order'] = ['id', 'desc'];
            $commentPaginateData = ModelUtil::paginate('blog_comment', $commentPage, $commentPageSize, $option);
            $comments = $commentPaginateData['records'];
            $commentTotal = $commentPaginateData['total'];
            if (modstart_module_enabled('Member')) {
                MemberUtil::mergeMemberUserBasics($comments);
            }
            foreach ($comments as $i => $comment) {
                $avatar = 'asset/image/avatar.svg';
                if (!empty($comment['_memberUser']['avatar'])) {
                    $avatar = $comment['_memberUser']['avatar'];
                }
                $comments[$i]['_avatar'] = AssetsUtil::fixFull($avatar);
            }
        }


        $recordNext = Blog::published()
            ->where('id', '>', $record['id'])
            ->orderBy('id', 'asc')
            ->limit(1)->first();
        if ($recordNext) {
            $recordNext = ArrayUtil::keepKeys($recordNext->toArray(), ['id', 'title']);
            $recordNext['_url'] = UrlUtil::blog($recordNext);
        }

        $recordPrev = Blog::published()
            ->where('id', '<', $record['id'])
            ->orderBy('id', 'desc')
            ->limit(1)->first();
        if ($recordPrev) {
            $recordPrev = ArrayUtil::keepKeys($recordPrev->toArray(), ['id', 'title']);
            $recordPrev['_url'] = UrlUtil::blog($recordPrev);
        }

        ModelUtil::increase(Blog::class, $record['id'], 'clickCount');

        return Response::generateSuccessData([
            'pageTitle' => $record['title'],
            'pageKeywords' => $record['seoKeywords'] ? $record['seoKeywords'] : $record['title'],
            'pageDescription' => $record['seoDescription'] ? $record['seoDescription'] : $record['summary'],
            'record' => $record,
            'recordNext' => $recordNext,
            'recordPrev' => $recordPrev,
            'commentPage' => $commentPage,
            'commentPageSize' => $commentPageSize,
            'commentTotal' => $commentTotal,
            'comments' => $comments,
        ]);
    }

    public function visitPasswordVerify()
    {
        $input = InputPackage::buildFromInput();
        $id = $input->getInteger('id');
        $record = ModelUtil::get('blog', $id);
        BizException::throwsIfEmpty('记录不存在', $record);
        BizException::throwsIf('记录数据异常', $record['visitMode'] != BlogVisitMode::PASSWORD);
        $password = $input->getTrimString('password');
        BizException::throwsIfEmpty('请输入密码', $password);
        BizException::throwsIf('密码错误', $password != $record['visitPassword']);
        $visitVerifiedIds = Session::get('Blog_VisitVerifiedIds');
        if (empty($visitVerifiedIds) || !is_array($visitVerifiedIds)) {
            $visitVerifiedIds = [];
        }
        if (!in_array($record['id'], $visitVerifiedIds)) {
            $visitVerifiedIds[] = $record['id'];
        }
        Session::put('Blog_VisitVerifiedIds', $visitVerifiedIds);
        return Response::generate(0, '验证成功', null, UrlUtil::blog($record));
    }
}

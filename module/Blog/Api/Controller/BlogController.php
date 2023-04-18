<?php


namespace Module\Blog\Api\Controller;


use Illuminate\Routing\Controller;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Core\BlogSuperSearchBiz;
use Module\Blog\Type\BlogCommentStatus;
use Module\Blog\Util\BlogCategoryUtil;
use Module\Member\Util\MemberUtil;


class BlogController extends Controller
{
    
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
                ['postTime' => ['lt' => date('Y-m-d H:i:s')]],
            ];
            $order = [
                ['isTop', 'desc'],
                ['postTime', 'desc'],
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
                    }


        $category = null;
        if ($categoryId > 0) {
            $category = \MBlog::getCategory($categoryId);
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
            'keywords' => $keywords,
            'markKeywords' => $markKeywords,
            'records' => $paginateData['records'],
            'total' => $paginateData['total'],
        ]);
    }

    
    public function get()
    {
        $input = InputPackage::buildFromInput();
        $id = $input->getInteger('id');
        $record = ModelUtil::get('blog', ['id' => $id]);
        BizException::throwsIfEmpty('记录不存在', $record);
        ModelUtil::decodeRecordJson($record, ['images']);
        $record['images'] = AssetsUtil::fixFull($record['images']);
        $record['tag'] = TagUtil::string2Array($record['tag']);
        $record['_category'] = BlogCategoryUtil::get($record['categoryId']);

        $commentPage = $input->getInteger('commentPage', 1);
        $commentPageSize = 10;

        $option = [];
        $option['where']['blogId'] = $record['id'];
        $option['where']['status'] = BlogCommentStatus::VERIFY_SUCCESS;

        $option['order'] = ['id', 'desc'];
        $commentPaginateData = ModelUtil::paginate('blog_comment', $commentPage, $commentPageSize, $option);

        $comments = $commentPaginateData['records'];
        if (modstart_module_enabled('Member')) {
            MemberUtil::mergeMemberUserBasics($comments);
        }

        $recordNext = ModelUtil::model('blog')
            ->where('postTime', '<', $record['postTime'])
            ->orderBy('postTime', 'desc')
            ->limit(1)->first();
        if ($recordNext) {
            $recordNext = ArrayUtil::keepKeys($recordNext->toArray(), ['id', 'title']);
        }

        $recordPrev = ModelUtil::model('blog')
            ->where('postTime', '>', $record['postTime'])
            ->orderBy('postTime', 'desc')
            ->limit(1)->first();
        if ($recordPrev) {
            $recordPrev = ArrayUtil::keepKeys($recordPrev->toArray(), ['id', 'title']);
        }

        ModelUtil::increase('blog', $record['id'], 'clickCount');

        return Response::generateSuccessData([
            'record' => $record,
            'recordNext' => $recordNext,
            'recordPrev' => $recordPrev,
            'commentPage' => $commentPage,
            'commentPageSize' => $commentPageSize,
            'commentTotal' => $commentPaginateData['total'],
            'comments' => $comments,
        ]);
    }
}

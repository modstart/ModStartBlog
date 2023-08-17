<?php


namespace Module\Blog\Api\Controller;


use Illuminate\Routing\Controller;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Core\Util\TimeUtil;
use Module\Blog\Model\Blog;


class ArchiveController extends Controller
{
    public function get()
    {
        $input = InputPackage::buildFromInput();
        $page = $input->getPage();
        $pageSize = 50;
        $year = $input->getInteger('year');
        if (empty($year)) {
            $year = date('Y');
        }
        $timeStart = date($year . '-01-01 00:00:00');
        $timeEnd = date($year . '-12-31 23:59:59');

        $month = $input->getInteger('month');
        if ($month > 0 && $month <= 12) {
            $timeStart = date($year . '-' . $month . '-01 00:00:00');
            $timeEnd = date($year . '-' . $month . '-31 23:59:59');
        }

        $result = Blog::where('postTime', '<', date('Y-m-d H:i:s'))
            ->where('postTime', '>=', date('Y-m-d H:i:s', strtotime($timeStart)))
            ->where('postTime', '<=', date('Y-m-d H:i:s', strtotime($timeEnd)))
            ->orderBy('postTime', 'asc')
            ->paginate($pageSize, ['id', 'title','isTop','isHot','isRecommend'], 'page', $page)->toArray();
        $paginateData = [
            'records' => $result['data'],
            'total' => $result['total'],
        ];

        $pageTitle = $year . '年';
        if ($month > 0 && $month <= 12) {
            $pageTitle .= $month . '月';
        }
        $pageTitle .= '博客归档';
        $pageKeywords = $pageTitle . ',博客归档';
        $pageDescription = $pageTitle . '，共' . $paginateData['total'] . '篇文章';

        $yearCounts = \MBlog::archiveYearCounts();
        $monthCounts = \MBlog::archiveMonthCounts($year);
        $yearCount = array_sum(ArrayUtil::flatItemsByKey($monthCounts, 'total'));

        $data = [];
        $data['page'] = $page;
        $data['pageSize'] = $pageSize;
        $data['records'] = $paginateData['records'];
        $data['total'] = $paginateData['total'];

        $data['year'] = $year;
        $data['month'] = $month;
        $data['monthCounts'] = $monthCounts;
        $data['yearCount'] = $yearCount;
        $data['yearCounts'] = $yearCounts;
        $data['pageTitle'] = $pageTitle;
        $data['pageKeywords'] = $pageKeywords;
        $data['pageDescription'] = $pageDescription;

        return Response::generateSuccessData($data);
    }
}

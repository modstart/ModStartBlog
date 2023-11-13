<?php


namespace Module\Blog\Api\Controller;


use Illuminate\Routing\Controller;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\HtmlUtil;
use Module\Blog\Type\BlogCommentStatus;
use Module\Blog\Type\BlogMessageStatus;
use Module\Member\Auth\MemberUser;
use Module\Member\Util\MemberUtil;
use Module\Vendor\Provider\Captcha\CaptchaProvider;


/**
 * @Api 博客系统
 */
class MessageController extends Controller
{
    /**
     * @Api 留言-列表
     * @ApiBodyParam page int 页码
     */
    public function paginate()
    {
        $input = InputPackage::buildFromInput();
        $page = $input->getInteger('page', 1);
        $pageSize = 10;

        $option = [];
        $option['where']['status'] = BlogMessageStatus::VERIFY_SUCCESS;
        $option['order'] = ['id', 'desc'];
        $paginateData = ModelUtil::paginate('blog_message', $page, $pageSize, $option);

        $records = $paginateData['records'];
        if (modstart_module_enabled('Member')) {
            MemberUtil::mergeMemberUserBasics($records);
        }
        foreach ($records as $i => $record) {
            $avatar = 'asset/image/avatar.svg';
            if (!empty($record['_memberUser']['avatar'])) {
                $avatar = $record['_memberUser']['avatar'];
            }
            $records[$i]['_avatar'] = AssetsUtil::fixFull($avatar);
        }

        return Response::generateSuccessData([
            'records' => $records,
            'total' => $paginateData['total'],
            'page' => $page,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * @Api 留言-新增
     * @ApiBodyParam username string 用户
     * @ApiBodyParam content string 内容
     * @ApiBodyParam email string 邮箱
     * @ApiBodyParam url string 网址
     */
    public function add()
    {
        $input = InputPackage::buildFromInput();
        $data = [];
        $data['username'] = $input->getTrimString('username');
        $data['content'] = $input->getTrimString('content');
        $data['email'] = $input->getTrimString('email');
        $data['url'] = $input->getTrimString('url');
        $ret = CaptchaProvider::get(modstart_config('Blog_MessageCaptchaProvider', 'default'))->validate();
        if (Response::isError($ret)) {
            return $ret;
        }
        BizException::throwsIfEmpty('内容为空', $data['content']);
        $data['content'] = HtmlUtil::text2html($data['content']);
        if (modstart_module_enabled('Member')) {
            if (MemberUser::isLogin()) {
                $data['username'] = MemberUser::get('username');
            }
            $data['memberUserId'] = MemberUser::id();
        } else {
            $data['memberUserId'] = 0;
        }
        $data['status'] = BlogCommentStatus::WAIT_VERIFY;
        ModelUtil::insert('blog_message', $data);
        return Response::generate(0, '提交成功，后台审核后将会显示', null, '[reload]');
    }
}

<?php


namespace Module\Blog\Core;


use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Widget\TextLink;
use Module\Blog\Util\UrlUtil;
use Module\MemberLike\Biz\AbstractMemberLikeBiz;

class BlogMemberLikeBiz extends AbstractMemberLikeBiz
{
    const NAME = 'Blog';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return '点赞的博客';
    }

    public function memberMenu()
    {
        return [
            'title' => '博客',
            'icon' => 'list',
            'sort' => 100,
            'children' => [
                [
                    'title' => '点赞的博客',
                    'url' => modstart_web_url('member_like/' . self::NAME),
                ],
            ]
        ];
    }

    public function memberTitle()
    {
        return '点赞的博客';
    }

    public function memberGridItem($item)
    {
        $record = ModelUtil::get('blog', $item['bizId']);
        return TextLink::primary(htmlspecialchars($record['title']),
            UrlUtil::blog($record), 'target="_blank"');
    }

    public function memberRecords($items)
    {
        ModelUtil::join($items, 'bizId', '_blog', 'blog', 'id');
        $records = [];
        foreach ($items as $item) {
            $record = ArrayUtil::keepKeys($item, ['biz', 'bizId', 'created_at']);
            $record['style'] = 'title';
            $record['title'] = '[已删除博客]';
            $record['url'] = '';
            if (!empty($item['_blog'])) {
                $record['title'] = $item['_blog']['title'];
                $record['url'] = '/pages/blog/show?hash=' . $item['_blog']['id'];
            }
            $records[] = $record;
        }
        return $records;
    }

}

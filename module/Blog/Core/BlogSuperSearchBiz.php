<?php


namespace Module\Blog\Core;


use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\HtmlUtil;
use ModStart\Core\Util\TagUtil;
use Module\Vendor\Provider\SuperSearch\AbstractSuperSearchBiz;
use Module\Vendor\Provider\SuperSearch\AbstractSuperSearchProvider;
use Module\Vendor\Provider\SuperSearch\FieldTypes;
use Module\Vendor\Provider\SuperSearch\SuperSearchProvider;

class BlogSuperSearchBiz extends AbstractSuperSearchBiz
{
    const NAME = 'blog';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return '博客';
    }

    public function providerName()
    {
        return modstart_config('Blog_BlogSuperSearchProvider');
    }

    public function fields()
    {
        return [
            'id' => ['type' => FieldTypes::F_LONG],
            'isPublished' => ['type' => FieldTypes::F_LONG],
            'isTop' => ['type' => FieldTypes::F_LONG],
            'postTime' => ['type' => FieldTypes::F_DATETIME],
            'categoryId' => ['type' => FieldTypes::F_LONG],
            'title' => ['type' => FieldTypes::F_TEXT],
            'summary' => ['type' => FieldTypes::F_TEXT],
            'content' => ['type' => FieldTypes::F_TEXT],
            'tag' => ['type' => FieldTypes::F_KEYWORD],
        ];
    }

    public function syncBatch(AbstractSuperSearchProvider $provider, $nextId)
    {
        $ret = ModelUtil::batch('blog', $nextId, 1000);
        BlogSuperSearchBiz::syncUpsert($ret['records'], false);
        $data = [];
        $data['count'] = count($ret['records']);
        $data['nextId'] = $ret['nextId'];
        return $data;
    }

    /**
     * @return AbstractSuperSearchProvider
     */
    public static function provider()
    {
        return SuperSearchProvider::get(modstart_config('Blog_BlogSuperSearchProvider'));
    }

    public static function syncUpsert($records, $checkExists = true)
    {
        $provider = self::provider();
        if (empty($provider)) {
            return;
        }
        if ($checkExists) {
            $provider->ensureBucket('blog');
        }
        foreach ($records as $record) {
            if (is_string($record['tag'])) {
                $tags = TagUtil::string2Array($record['tag']);
            } else {
                $tags = $record['tag'];
            }
            $provider->upsert('blog', $record['id'], [
                'id' => intval($record['id']),
                'isPublished' => intval($record['isPublished']),
                'isTop' => intval($record['isTop']),
                'postTime' => $record['postTime'],
                'categoryId' => intval($record['categoryId']),
                'title' => $record['title'],
                'summary' => $record['summary'],
                'content' => HtmlUtil::text($record['content']),
                'tag' => $tags,
            ]);
        }
    }

    public static function syncDelete($id)
    {
        $provider = self::provider();
        if (empty($provider)) {
            return;
        }
        $provider->delete('blog', $id);
    }
}

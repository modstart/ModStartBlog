<?php


namespace Module\Blog\Type;


use Illuminate\Support\Str;
use ModStart\Core\Type\BaseType;
use ModStart\Core\Util\FileUtil;
use ModStart\Core\View\ResponsiveView;

class BlogCategoryTemplateView implements BaseType
{
    public static function getList()
    {
        $map = [
            '' => '默认',
        ];
        $templateRoot = ResponsiveView::templateRootRealpath('Blog');
        $files = FileUtil::listFiles($templateRoot . 'pc/blog', '*.blade.php');
        $files = array_filter($files, function ($file) {
            return Str::startsWith($file['filename'], 'list');
        });
        foreach ($files as $file) {
            $name = $file['filename'];
            $name = substr($name, 0, -strlen('.blade.php'));
            $map[$name] = $name;
        }
        return $map;
    }
}

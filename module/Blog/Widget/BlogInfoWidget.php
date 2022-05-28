<?php

namespace Module\Blog\Widget;

use ModStart\Widget\AbstractWidget;

class BlogInfoWidget extends AbstractWidget
{
    protected $view = 'module::Blog.View.widget.blogInfo';

    protected function variables()
    {
        return [
            'attributes' => $this->formatAttributes(),
        ];
    }
}

<div class="ub-content-box margin-bottom">
    <div class="tw-px-3">
        <div class="tw-text-lg">
            <i class="fa fa-fire"></i>
            热门博客
        </div>
    </div>
    <div class="tw-px-1">
        <div class="tw-mt-4">
            @foreach(\MBlog::hottestBlog(5) as $b)
                <div class="tw-pb-2">
                    <a href="{{modstart_web_url('blog/'.$b['id'])}}"
                       class="tw-block tw-overflow-ellipsis tw-overflow-hidden ub-text-default tw-truncate">
                        <i class="iconfont icon-angle-right ub-text-muted tw-mr-1"></i>
                        {{$b['title']}}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

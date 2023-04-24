<div class="ub-content-box margin-bottom">
    <div class="tw-p-3">
        <div class="tw-text-lg">
            <i class="iconfont icon-list"></i>
            最新博客
        </div>
        <div class="tw-mt-4">
            @foreach(MBlog::latestBlog(5) as $b)
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

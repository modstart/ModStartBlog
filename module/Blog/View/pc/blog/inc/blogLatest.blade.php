<div class="tw-p-6 margin-top tw-bg-white tw-rounded">
    <div class="tw-text-lg">
        <i class="iconfont icon-list ub-text-primary"></i>
        最新博客
    </div>
    <div class="tw-mt-4">
        @foreach(MBlog::latestBlog(5) as $b)
            <div class="tw-pb-2">
                <a href="{{modstart_web_url('blog/'.$b['id'])}}"
                   class="tw-block tw-overflow-ellipsis tw-overflow-hidden tw-text-gray-800 tw-truncate">
                    <i class="iconfont icon-angle-right ub-text-muted tw-mr-1"></i>
                    {{$b['title']}}
                </a>
            </div>
        @endforeach
    </div>
</div>

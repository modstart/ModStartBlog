<div class="tw-p-6 margin-top tw-bg-white tw-rounded">
    <div class="tw-text-lg">
        <i class="iconfont icon-list-alt"></i>
        最新博客
    </div>
    <div class="tw-mt-4">
        @foreach(MBlog::latestBlog(5) as $b)
            <div class="tw-pb-2">
                <a href="{{modstart_web_url('blog/'.$b['id'])}}" class="tw-text-gray-800">
                    <i class="iconfont icon-angle-right ub-text-muted tw-mr-1"></i>
                    {{$b['title']}}
                </a>
            </div>
        @endforeach
    </div>
</div>

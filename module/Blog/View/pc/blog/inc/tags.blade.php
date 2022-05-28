<div class="tw-p-6 margin-top tw-bg-white tw-rounded">
    <div class="tw-text-lg">
        <i class="iconfont icon-tag"></i>
        标签
    </div>
    <div class="tw-mt-4 tw-flex tw-flex-wrap">
        @foreach(MBlog::tags() as $t=>$c)
            <a href="{{modstart_web_url('blogs',['keywords'=>$t])}}"
               class="tw-rounded-3xl tw-text-gray-600 tw-block tw-bg-gray-100 tw-leading-8 tw-mb-3 tw-px-2 tw-mr-2 @if(!empty($keywords)&&$keywords==$t) ub-bg-primary ub-text-white @endif">
                {{$t}}
                <span class="tw-rounded-3xl tw-bg-gray-300 tw-text-white tw-px-2">
                    {{$c?$c:0}}
                </span>
            </a>
        @endforeach
    </div>
</div>

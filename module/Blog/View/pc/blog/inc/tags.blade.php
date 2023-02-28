<div class="tw-p-6 margin-top tw-bg-white tw-rounded">
    <div class="tw-text-lg">
        <i class="iconfont icon-tag ub-text-primary"></i>
        标签
    </div>
    <div class="tw-mt-4 tw-flex tw-flex-wrap">
        @foreach(MBlog::tags() as $t=>$c)
            <a href="{{modstart_web_url('blogs',['keywords'=>$t])}}"
               class="hover:tw-shadow tw-bg-gray-100 tw-block tw-leading-5 tw-mb-2 tw-mr-2 tw-px-2 tw-py-1 tw-rounded-3xl tw-text-gray-600 @if(!empty($keywords)&&$keywords==$t) ub-bg-primary ub-text-white @endif">
                {{$t}}
                <span class="tw-rounded-3xl tw-bg-gray-300 tw-text-white tw-px-2">
                    {{$c?$c:0}}
                </span>
            </a>
        @endforeach
    </div>
</div>

<div class="ub-content-box margin-bottom">
    <div class="tw-p-3">
        <div class="tw-text-lg">
            <i class="iconfont icon-tag"></i>
            标签
        </div>
        <div class="tw-mt-4 tw-flex tw-flex-wrap">
            @foreach(MBlog::tags() as $t=>$c)
                <a href="{{modstart_web_url('blogs',['keywords'=>$t])}}"
                   class="hover:tw-shadow ub-content-block tw-block tw-leading-5 tw-mb-2 tw-mr-2 tw-px-2 tw-py-1 tw-rounded-3xl @if(!empty($keywords)&&$keywords==$t) ub-bg-primary @endif">
                    {{$t}}
                    <span class="tw-rounded-3xl tw-bg-gray-400 tw-text-white tw-px-1 tw-opacity-50">
                        {{$c?$c:0}}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>

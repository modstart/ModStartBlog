@if(empty($records))
    <div class="ub-empty">
        <div class="icon">
            <div class="iconfont icon-empty-box"></div>
        </div>
        <div class="text">
            暂无数据
        </div>
    </div>
@endif
@foreach($records as $record)
    <div class="ub-text-blog tw-border-0 ub-border-bottom tw-py-2">
        <div>
            <div class="lg:tw-truncate">
                <i class="iconfont icon-dot-sm"></i>
                @if($record['isTop'])
                    <span class="tw tw-align-top tw-inline-block tw-leading-6 tw-px-3 tw-rounded tw-bg-blue-100 tw-text-blue-500">置顶</span>
                @endif
                @if($record['isHot'])
                    <span class="tw tw-align-top tw-inline-block tw-leading-6 tw-px-3 tw-rounded tw-bg-red-100 tw-text-red-500">
                        <i class="fa fa-fire"></i>
                        热门
                    </span>
                @endif
                @if($record['isRecommend'])
                    <span class="tw tw-align-top tw-inline-block tw-leading-6 tw-px-3 tw-rounded tw-bg-yellow-100 tw-text-yellow-500">
                        <i class="iconfont icon-thumb-up"></i>
                        推荐
                    </span>
                @endif
                <a href="{{modstart_web_url('blog/'.$record['id'])}}"
                   class="pb-keywords-highlight tw-align-top tw-inline-block tw-leading-6 default">
                    {{$record['title']}}
                </a>
            </div>
        </div>
    </div>
@endforeach

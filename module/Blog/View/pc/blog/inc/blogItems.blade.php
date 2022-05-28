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
    <div class="ub-text-blog tw-border-0 tw-border-b tw-border-gray-100 tw-border-solid tw-pb-6 tw-mb-6 tw-flex"
         data-scroll-animate="animated fadeInUp"
    >
        <div class="tw-flex-grow tw-overflow-hidden">
            <div class="ub-text-truncate">
                <a href="{{modstart_web_url('blog/'.$record['id'])}}" class="pb-keywords-highlight tw-text-xl tw-text-gray-800">
                    {{$record['title']}}
                </a>
            </div>
            <div class="tw-text-gray-400 tw-pt-2">
                @if($record['_category'])
                    <i class="iconfont icon-category"></i>
                    {{$record['_category']['title']}}
                    <span>&nbsp;</span>
                @endif
                <i class="iconfont icon-time"></i>
                {{\Carbon\Carbon::parse($record['postTime'])->format('Y-m-d H:i')}}
                <span>&nbsp;</span>
                <i class="iconfont icon-eye"></i>
                {{$record['clickCount']?:0}}
                <span>&nbsp;</span>
                <i class="iconfont icon-comment"></i>
                {{$record['commentCount']?:0}}
                <span>&nbsp;</span>
            </div>
            <div class="tw-text-gray-400 tw-pt-2 tw-h-14 tw-leading-6 tw-overflow-hidden">
                {{$record['summary']}}
            </div>
            @if(!empty($record['tag']))
                <div class="tw-pt-2 tw-flex tw-flex-wrap pb-keywords-highlight">
                    @foreach($record['tag'] as $t)
                        <a href="{{modstart_web_url('blogs',['keywords'=>$t])}}"
                           class="tw-rounded-3xl tw-text-gray-400 tw-block tw-bg-gray-100 tw-leading-6 tw-mb-3 tw-px-2 tw-mr-2">
                            {{$t}}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
        @if(!empty($record['_cover']))
            <div class="tw-w-40 tw-ml-4 tw-flex-shrink-0">
                <div class="ub-cover-3-2 tw-rounded" style="background-image:url({{$record['_cover']}})"></div>
            </div>
        @endif
    </div>
@endforeach

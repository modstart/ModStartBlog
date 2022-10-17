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
<div class="row">
    @foreach($records as $record)
        <div class="col-md-4">
            <div class="ub-text-blog"
                 data-scroll-animate="animated fadeInUp"
            >
                <a class="lg:tw-w-40 tw-w-20 tw-ml-4 tw-flex-shrink-0" href="{{modstart_web_url('blog/'.$record['id'])}}">
                    <div class="ub-cover-3-2 tw-rounded" style="background-image:url({{empty($record['_cover'])?'':$record['_cover']}})"></div>
                </a>
                <div class="ub-text-truncate tw-text-center">
                    <a href="{{modstart_web_url('blog/'.$record['id'])}}" class="pb-keywords-highlight tw-text-gray-800">
                        {{$record['title']}}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

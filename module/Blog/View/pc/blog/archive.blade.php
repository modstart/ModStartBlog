@extends($_viewFrame)

@section('pageTitle'){{$pageTitle}}@endsection
@section('pageKeywords'){{$pageKeywords}}@endsection
@section('pageDescription'){{$pageDescription}}@endsection

{!! \ModStart\ModStart::js('asset/common/scrollAnimate.js') !!}

@include('module::Blog.View.pc.blog.inc.theme')

@section('bodyContent')

    <div class="ub-container margin-top">
        <div class="row">
            <div class="col-md-8">
                <div class="ub-content-box margin-bottom">
                    <div class="tw-p-3">
                        <a href="?{!! \ModStart\Core\Input\Request::mergeQueries(['month'=>null]) !!}"
                           class="btn btn-round {{!$month?'btn-primary':''}}">
                            {{$year}}年全部({{$yearCount}})
                        </a>
                        @foreach($monthCounts as $mc)
                            <a href="?{!! \ModStart\Core\Input\Request::mergeQueries(['month'=>$mc['month']]) !!}"
                               class="btn btn-round {{$month==$mc['month']?'btn-primary':''}}">
                                {{$mc['month']}}月({{$mc['total']}})
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="ub-content-box margin-bottom">
                    <div class="tw-p-3">

                        @include('module::Blog.View.pc.blog.inc.blogItemsTitle')

                        <div>
                            <div class="ub-page">
                                {!! $pageHtml !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 margin-bottom">

                <div class="ub-content-box margin-bottom">
                    <div class="tw-p-3">
                        <div class="tw-text-lg">
                            <i class="iconfont icon-category"></i>
                            年份归档
                        </div>
                        <div class="tw-mt-4">
                            <div class="row">
                                @foreach($yearCounts as $yc)
                                    <div class="col-12">
                                        <a href="{{modstart_web_url('blog/archive',['year'=>$yc['year']])}}"
                                           class="hover:tw-shadow tw-block ub-content-block tw-rounded tw-leading-10 tw-mb-3 tw-px-2 tw-truncate @if($year==$yc['year']) ub-text-primary @endif">
                                            <i class="iconfont icon-angle-right ub-text-muted"></i>
                                            {{$yc['year']}}年
                                            （{{$yc['total']}}）
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

@endsection

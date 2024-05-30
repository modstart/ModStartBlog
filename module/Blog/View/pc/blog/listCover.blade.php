@extends($_viewFrame)

@section('pageTitle'){{$pageTitle}}@endsection
@section('pageKeywords'){{$pageKeywords}}@endsection
@section('pageDescription'){{$pageDescription}}@endsection

{!! \ModStart\ModStart::js('asset/common/scrollAnimate.js') !!}

{!! \ModStart\ModStart::js('asset/vendor/jqueryMark.js') !!}
{!! \ModStart\ModStart::style('[data-markjs]{color:red !important;background:transparent;}') !!}
{!! \ModStart\ModStart::script("$('.pb-keywords-highlight').mark(".\ModStart\Core\Util\SerializeUtil::jsonEncode($keywords).",{});") !!}

@include('module::Blog.View.pc.blog.inc.theme')

@section('bodyContent')

    <div class="ub-container margin-top">
        <div class="row">
            <div class="col-md-8">
                @if($keywords)
                    <div class="ub-content-box margin-bottom">
                        <div class="tw-p-3 tw-text-lg">
                            <i class="iconfont icon-search"></i>
                            搜索
                            <span class="ub-text-primary ub-text-bold">{{$keywords}}</span>
                            共找到
                            <span class="ub-text-primary ub-text-bold">{{$total}}</span>
                            条记录
                            <a href="?{{\ModStart\Core\Input\Request::mergeQueries(['keywords'=>null])}}" class="ub-text-muted">
                                <i class="iconfont icon-close"></i>
                            </a>
                        </div>
                    </div>
                @endif
                @if($category)
                    <div class="ub-content-box margin-bottom">
                        <div class="tw-p-3 tw-text-lg">
                            <i class="iconfont icon-category"></i>
                            <a href="{{modstart_web_url('blogs')}}" class="tw-text-lg ub-text-default">
                                全部
                            </a>
                            <i class="iconfont icon-angle-right ub-text-default"></i>
                            <a href="{{modstart_web_url('blogs',['categoryId'=>$category])}}" class="tw-text-lg ub-text-default">
                                {{$category['title']}}
                            </a>
                        </div>
                    </div>
                @endif

                <div class="ub-content-box margin-bottom">
                    <div class="tw-p-3">
                        @include('module::Blog.View.pc.blog.inc.blogItemsCover')
                        <div>
                            <div class="ub-page">
                                {!! $pageHtml !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4 margin-bottom">

                @include('module::Blog.View.pc.blog.inc.info')

                @include('module::Blog.View.pc.blog.inc.categories')

                @include('module::Blog.View.pc.blog.inc.tags')

                @include('module::Blog.View.pc.blog.inc.partners')

            </div>
        </div>
    </div>

@endsection

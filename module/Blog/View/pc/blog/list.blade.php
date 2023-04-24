@extends($_viewFrame)

@section('pageTitle'){{$pageTitle?$pageTitle.' | '.modstart_config('siteName'):modstart_config('siteName')}}@endsection
@section('pageKeywords'){{$pageKeywords}}@endsection
@section('pageDescription'){{$pageDescription}}@endsection

{!! \ModStart\ModStart::js('asset/common/scrollAnimate.js') !!}

{!! \ModStart\ModStart::js('asset/vendor/jqueryMark.js') !!}
{!! \ModStart\ModStart::style('[data-markjs]{color:red !important;background:transparent;}') !!}
{!! \ModStart\ModStart::script("$('.pb-keywords-highlight').mark(".json_encode($markKeywords).".join(' '),{separateWordSearch:true});") !!}

@if(\Module\Blog\Util\BlogThemeUtil::isDark())
@section('htmlProperties')data-theme="dark"@endsection
@endif

@section('bodyContent')

    <div class="ub-container margin-top">
        <div class="row">
            <div class="col-md-8">
                @if($keywords)
                    <div class="ub-content-box margin-bottom">
                        <div class="tw-p-3">
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
                            <div class="tw-p-3">
                                <i class="iconfont icon-category"></i>
                                <a href="{{modstart_web_url('blogs')}}" class="tw-text-lg tw-text-gray-400">
                                    全部
                                </a>
                                <i class="iconfont icon-angle-right ub-text-muted"></i>
                                <a href="{{modstart_web_url('blogs',['categoryId'=>$category])}}" class="tw-text-lg tw-text-gray-800">
                                    {{$category['title']}}
                                </a>
                            </div>
                        </div>
                @endif
                <div class="ub-content-box margin-bottom">
                    <div class="tw-p-3">

                        @include('module::Blog.View.pc.blog.inc.blogItems')

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

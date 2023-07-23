@extends($_viewFrame)

@section('pageTitle'){{modstart_config('siteName')}} | {{modstart_config('siteSlogan')}}@endsection
@section('pageKeywords'){{modstart_config('siteKeywords')}}@endsection
@section('pageDescription'){{modstart_config('siteDescription')}}@endsection

{!! \ModStart\ModStart::js('asset/common/scrollAnimate.js') !!}

@include('module::Blog.View.pc.blog.inc.theme')

@section('bodyContent')

    <div class="ub-container margin-top">
        <div class="row">
            <div class="col-md-8 margin-bottom">

                <div class="ub-content-box margin-bottom tw-overflow-hidden" style="padding:0;">
                    {!! \Module\Banner\View\BannerView::basic('Blog',null,'5-2','5-3') !!}
                </div>

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

                @include('module::Blog.View.pc.blog.inc.blogHottest')

                @include('module::Blog.View.pc.blog.inc.partners')

            </div>
        </div>
    </div>

@endsection

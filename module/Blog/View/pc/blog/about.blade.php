@extends($_viewFrame)

@section('pageTitleMain')关于博主@endsection
@section('pageKeywords')关于博主@endsection
@section('pageDescription')关于博主@endsection

@include('module::Blog.View.pc.blog.inc.theme')

@section('bodyContent')

    <div class="ub-container margin-top">
        <div class="row">
            <div class="col-md-8 margin-bottom">

                <div class="ub-content-box">
                    <div class="tw-p-3">
                        <div class="tw-text-lg">
                            <i class="iconfont icon-user"></i>
                            关于我
                        </div>
                        <div class="tw-mt-4">
                            <div class="ub-html lg">
                                {!! modstart_config('Blog_AboutContent') !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4 margin-bottom">

                @include('module::Blog.View.pc.blog.inc.info')

                @include('module::Blog.View.pc.blog.inc.contact')

                @include('module::Blog.View.pc.blog.inc.categories')

                @include('module::Blog.View.pc.blog.inc.blogLatest')

            </div>
        </div>
    </div>

@endsection

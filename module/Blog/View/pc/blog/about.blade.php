@extends($_viewFrame)

@section('pageTitleMain')关于博主@endsection
@section('pageKeywords')关于博主@endsection
@section('pageDescription')关于博主@endsection

@section('bodyContent')

    <div class="ub-container">
        <div class="row">
            <div class="col-md-8 margin-top">

                <div class="tw-p-6  tw-bg-white tw-rounded">
                    <div class="tw-text-lg">
                        <i class="iconfont icon-user"></i>
                        关于我
                    </div>
                    <div class="tw-mt-4">
                        <div class="ub-html">
                            {!! modstart_config('Blog_AboutContent') !!}
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4 margin-top">

                @include('module::Blog.View.pc.blog.inc.info')

                @include('module::Blog.View.pc.blog.inc.contact')

                @include('module::Blog.View.pc.blog.inc.categories')

                @include('module::Blog.View.pc.blog.inc.blogLatest')

            </div>
        </div>
    </div>

@endsection

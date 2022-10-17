@extends($_viewFrame)

@section('pageTitleMain')博客标签@endsection
@section('pageKeywords')博客标签@endsection
@section('pageDescription')博客标签@endsection

@section('bodyContent')

    <div class="ub-container">
        <div class="row">
            <div class="col-md-8 margin-top">

                <div class="tw-p-6  tw-bg-white tw-rounded">
                    <div class="tw-text-lg">
                        <i class="iconfont icon-tag"></i>
                        博客标签
                    </div>
                    <div class="tw-mt-4">
                        @foreach($tags as $tag)
                            <a href="{{modstart_web_url('blogs',['keywords'=>$tag['name']])}}"
                                   class="tw-rounded-3xl tw-text-gray-600 tw-inline-block tw-bg-gray-100 tw-leading-8 tw-mb-3 tw-px-2 tw-mr-2 @if(!empty($keywords)&&$keywords==$t) ub-bg-primary ub-text-white @endif">
                                {{$tag['name']}}
                                <span class="tw-rounded-3xl tw-bg-gray-300 tw-text-white tw-px-2">
                                    {{$tag['count']?$tag['count']:0}}
                                </span>
                            </a>
                        @endforeach
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

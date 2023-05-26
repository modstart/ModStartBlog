@extends($_viewFrame)

@section('pageTitleMain')博客标签@endsection
@section('pageKeywords')博客标签@endsection
@section('pageDescription')博客标签@endsection

@include('module::Blog.View.pc.blog.inc.theme')

@section('bodyContent')

    <div class="ub-container margin-top">
        <div class="row">
            <div class="col-md-8">

                <div class="ub-content-box margin-bottom">
                    <div class="tw-p-3">
                        <div class="tw-text-lg">
                            <i class="iconfont icon-tag"></i>
                            博客标签
                        </div>
                        <div class="tw-mt-4">
                            @foreach($tags as $t)
                                <a href="{{modstart_web_url('blogs',['keywords'=>$t['name']])}}"
                                   class="hover:tw-shadow ub-content-block tw-inline-block tw-leading-5 tw-mb-2 tw-mr-2 tw-px-2 tw-py-1 tw-rounded-3xl @if(!empty($keywords)&&$keywords==$t) ub-bg-primary @endif">
                                    {{$t['name']}}
                                    <span class="tw-rounded-3xl tw-bg-gray-400 tw-text-white tw-px-1 tw-opacity-50">
                                        {{$t['count']?$t['count']:0}}
                                    </span>
                                </a>
                            @endforeach
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

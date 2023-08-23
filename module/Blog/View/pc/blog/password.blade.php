@extends($_viewFrame)

@section('pageTitleMain'){{$record['title']}}@endsection
@section('pageKeywords'){{$record['seoKeywords']?$record['seoKeywords']:$record['title']}}@endsection
@section('pageDescription'){{$record['seoDescription']?$record['seoDescription']:$record['summary']}}@endsection

{!! \ModStart\ModStart::js('asset/common/timeago.js') !!}

@include('module::Blog.View.pc.blog.inc.theme')

@section('bodyContent')

    <div class="ub-container margin-top">
        <div class="row">
            <div class="col-md-8 margin-bottom">

                <div class="tw-p-6 tw-rounded ub-content-box tw-py-4 margin-bottom">
                    <h1 class="tw-mb-4">
                        {{$record['title']}}
                    </h1>
                    <div class="tw-text-gray-400 tw-pb-4 tw-mb-4 ub-border-bottom">
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
                    <div>
                        <form action="{{\Module\Blog\Util\UrlUtil::blogVisitPasswordVerify()}}" method="post" data-ajax-form>
                            <input type="hidden" name="id" value="{{$record['id']}}" />
                            <div class="tw-py-20 tw-mx-auto" style="max-width:10rem;">
                                <div class="ub-form vertical">
                                    <div class="line">
                                        <div class="field">
                                            <input type="text" name="password" class="form-lg tw-text-center" placeholder="请输入密码" />
                                        </div>
                                    </div>
                                    <div class="line">
                                        <div class="field">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">提交验证</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(modstart_module_enabled('ShareJS'))
                        <div class="tw-text-center">
                            {!! \Module\ShareJS\View\ShareJSView::buttons() !!}
                        </div>
                    @endif
                </div>


                <div class="tw-p-6 margin-bottom ub-content-box tw-rounded">
                    <div class="row">
                        <div class="col-6">
                            <div>
                                <div class="ub-text-default tw-text-sm">上一篇</div>
                                <div class="tw-pt-2">
                                    @if($recordPrev)
                                        <a href="{{modstart_web_url('blog/'.$recordPrev['id'])}}" class="ub-text-default tw-inline-block">
                                            {{$recordPrev['title']}}
                                        </a>
                                    @else
                                        <span class="ub-text-default">没有了</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="tw-text-right">
                                <div class="ub-text-default tw-text-sm">下一篇</div>
                                <div class="tw-pt-2">
                                    @if($recordNext)
                                        <a href="{{modstart_web_url('blog/'.$recordNext['id'])}}" class="ub-text-default tw-inline-block">
                                            {{$recordNext['title']}}
                                        </a>
                                    @else
                                        <span class="ub-text-default">没有了</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">

                @include('module::Blog.View.pc.blog.inc.categories')

                @include('module::Blog.View.pc.blog.inc.blogLatest')

            </div>
        </div>
    </div>

@endsection

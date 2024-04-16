@extends($_viewFrame)

@section('pageTitleMain'){{$record['title']}}@endsection
@section('pageKeywords'){{$record['seoKeywords']?$record['seoKeywords']:$record['title']}}@endsection
@section('pageDescription'){{$record['seoDescription']?$record['seoDescription']:$record['summary']}}@endsection

{!! \ModStart\ModStart::js('asset/common/timeago.js') !!}

@include('module::Blog.View.pc.blog.inc.theme')

@section('headAppend')
    @parent
    @if(modstart_config('Blog_ContentNavEnable',false))
    <style>
        #pageContentHeading > * {
            margin-right:0.5rem;
            margin-top:0.5rem;
        }
    </style>
    <script>
        $(function(){
            if($(window).width()>800){
                var htmlNavOption = {
                    positionOffset: $('#pageContentContainer').offset().top - 60,
                };
                MS.ui.htmlNav('#pageContent', '#pageContentHeading', htmlNavOption);
            }
        });
    </script>
    @endif
@endsection

@section('bodyContent')

    <div class="ub-container margin-top">
        <div class="row">
            <div class="col-md-8 margin-bottom">

                <div class="tw-p-6 tw-rounded ub-content-box tw-py-4 margin-bottom">
                    <h1 class="tw-mb-4">
                        {{$record['title']}}
                    </h1>
                    <div class="tw-text-gray-400 tw-pb-4 tw-mb-4 ub-border-bottom">
                        @if(\ModStart\Admin\Auth\Admin::isLogin())
                            <div class="tw-float-right">
                                <a href="javascript:;"
                                   data-dialog-width="80%"
                                   data-dialog-height="90%"
                                   data-dialog-request="{{modstart_admin_url('blog/blog/edit',['_id'=>$record['id']])}}">
                                    <i class="iconfont icon-edit"></i>
                                    编辑
                                </a>
                            </div>
                        @endif
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
                        @if(modstart_module_enabled('MemberFav'))
                            <i class="iconfont icon-star-alt"></i>
                            {{ $record['favCount']?:0 }}
                            <span>&nbsp;</span>
                        @endif
                        @if(modstart_module_enabled('MemberLike'))
                            <i class="iconfont icon-heart-alt"></i>
                            {{ $record['likeCount']?:0 }}
                            <span>&nbsp;</span>
                        @endif
                    </div>
                    <div class="tw-relative" id="pageContentContainer">
                        @if(!empty($record['images']))
                            @foreach($record['images'] as $image)
                                <div>
                                    <img class="tw-w-full tw-rounded" src="{{$image}}" />
                                </div>
                            @endforeach
                        @endif
                        <div id="pageContent" class="ub-html lg">
                            {!! $record['content'] !!}
                        </div>
                        <div id="pageContentHeading"></div>
                    </div>
                    @if(modstart_module_enabled('MemberFav')||modstart_module_enabled('MemberLike'))
                        <div class="tw-text-center margin-bottom">
                            @if(modstart_module_enabled('MemberFav'))
                                @include('module::MemberFav.View.inc.favBtn',['biz'=>\Module\Blog\Core\BlogMemberFavBiz::NAME,'bizId'=>$record['id']])
                            @endif
                            @if(modstart_module_enabled('MemberLike'))
                                @include('module::MemberLike.View.inc.likeBtn',['biz'=>\Module\Blog\Core\BlogMemberLikeBiz::NAME,'bizId'=>$record['id']])
                            @endif
                        </div>
                    @endif
                    @if(modstart_module_enabled('ShareJS'))
                        <div class="tw-text-center margin-bottom">
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

                @if(modstart_module_enabled('Reward'))
                    <div class="tw-p-4 margin-bottom ub-block-box tw-rounded-lg">
                        @include('module::Reward.View.pc.public.reward',[ 'biz'=>'blog', 'bizId'=>$record['id'] ])
                    </div>
                @endif

                @if(modstart_config('Blog_CommentEnable',false))
                    <div class="tw-p-6 margin-bottom ub-content-box tw-rounded">
                        <div class="tw-text-lg">
                            <i class="iconfont icon-comment"></i>
                            博客评论
                        </div>
                        <div class="tw-mt-4">
                            @if(empty($comments))
                                <div class="ub-empty">
                                    <div class="icon">
                                        <div class="iconfont icon-empty-box"></div>
                                    </div>
                                    <div class="text">
                                        还没有人评论，赶紧抢个沙发~
                                    </div>
                                </div>
                            @endif
                            @foreach($comments as $comment)
                                <div class="tw-border-0 tw-border-b tw-border-solid tw-border-gray-100 tw-pb-6 tw-mb-6">
                                    <div class="tw-flex">
                                        <div class="tw-w-10">
                                            <div class="ub-cover-1-1 tw-rounded-full"
                                                 style="background-image:url({{$comment['_avatar']}})"></div>
                                        </div>
                                        <div class="tw-flex-grow tw-ml-4">
                                            <div class="tw-leading-6 tw-text-lg">
                                                {{$comment['username']?$comment['username']:'匿名用户'}}
                                            </div>
                                            <div class="tw-leading-4 ub-text-muted">
                                                <time datetime="{{$comment['created_at']}}"></time>
                                                说：
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tw-mt-4 tw-pl-14">
                                        <div class="ub-html lg">
                                            {!! $comment['content'] !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div>
                            <div class="ub-page">
                                {!! $commentPageHtml !!}
                            </div>
                        </div>
                    </div>


                    <div class="tw-p-6 margin-bottom ub-content-box tw-rounded">
                        <div class="tw-text-lg">
                            <i class="iconfont icon-comment"></i>
                            发表评论
                        </div>
                        <div class="tw-mt-4" onclick="$('.pb-blog-comment-submit-box').css({height:'auto'})">
                            <form method="post" data-ajax-form action="{{modstart_api_url('blog/comment/add')}}">
                                <input type="hidden" name="blogId" value="{{$record['id']}}" />
                                <div class="pb-blog-comment">
                                    <div class="tw-flex">
                                        <div class="tw-w-10">
                                            <div class="ub-cover-1-1 tw-rounded-full" style="background-image:url({{\ModStart\Core\Assets\AssetsUtil::fixOrDefault(!empty($_memberUser)?$_memberUser['avatar']:'','asset/image/avatar.svg')}})"></div>
                                        </div>
                                        <div class="tw-flex-grow tw-leading-10 tw-ml-4 tw-text-lg">
                                            @if(!empty($_memberUser))
                                                <input type="text" name="username" class="form-lg"
                                                       style="border:none;border-bottom:1px solid var(--color-body-line);background:transparent;" value="{{$_memberUser['username']}}" readonly />
                                            @else
                                                <input type="text" name="username" class="form-lg"
                                                       style="border:none;border-bottom:1px solid var(--color-body-line);" placeholder="匿名用户" value="{{!empty($_memberUser)?$_memberUser['username']:''}}" />
                                            @endif
                                        </div>
                                        @if(modstart_module_enabled('Member'))
                                            <div class="tw-pt-4">
                                                @if(\Module\Member\Auth\MemberUser::isNotLogin())
                                                    <a href="{{modstart_web_url('login',['redirect'=>\ModStart\Core\Input\Request::currentPageUrl()])}}" class="tw-text-gray-400">
                                                        <i class="iconfont icon-user-o"></i>
                                                        登录
                                                    </a>
                                                @else
                                                    <a href="{{modstart_web_url('member')}}" class="tw-text-gray-400">
                                                        <i class="iconfont icon-user-o"></i>
                                                        已登录
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="tw-mt-4">
                                        <textarea class="form-lg tw-w-full tw-resize-none" name="content"
                                                  style="height:5rem;" placeholder="输入想说的话"></textarea>
                                    </div>
                                    <div class="pb-blog-comment-submit-box tw-h-0 tw-overflow-hidden">
                                        <div>
                                            <div class="row">
                                                <div class="col-md-6 tw-mt-4">
                                                    <input type="text" class="form-lg tw-w-full" name="email" placeholder="输入邮箱" />
                                                </div>
                                                <div class="col-md-6 tw-mt-4">
                                                    <input type="text" class="form-lg tw-w-full" name="url" placeholder="输入网站" />
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row">
                                                <div class="col-md-6 tw-mt-4">
                                                    {!! \Module\Vendor\Provider\Captcha\CaptchaProvider::get(modstart_config('Blog_BlogCaptchaProvider','default'))->render() !!}
                                                </div>
                                                <div class="col-md-6 tw-mt-4">
                                                    <button type="submit" class="btn btn-lg btn-block">
                                                        <i class="iconfont icon-direction-right"></i>
                                                        提交
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tw-mt-4 tw-text-gray-400">
                                            <b>说明：</b>请文明发言，共建和谐网络，您的个人信息不会被公开显示。
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
            <div class="col-md-4">

                @include('module::Blog.View.pc.blog.inc.categories')

                @include('module::Blog.View.pc.blog.inc.blogLatest')

            </div>
        </div>
    </div>

@endsection

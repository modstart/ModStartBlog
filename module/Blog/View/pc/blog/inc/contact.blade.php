<div class="tw-p-6 margin-top tw-bg-white tw-rounded">
    <div class="tw-text-lg">
        <i class="iconfont icon-user"></i>
        联系方式
    </div>
    <div class="tw-mt-4">
        @if(modstart_config('Blog_ContactQQ'))
        <div class="ub-pair">
            <div class="name">QQ</div>
            <div class="value">
                {{modstart_config('Blog_ContactQQ')}}
            </div>
        </div>
        @endif
        @if(modstart_config('Blog_ContactEmail'))
        <div class="ub-pair">
            <div class="name">邮箱</div>
            <div class="value">
                <a class="tw-text-gray-600" href="mailto:{{modstart_config('Blog_ContactEmail')}}">{{modstart_config('Blog_ContactEmail')}}</a>
            </div>
        </div>
        @endif
        @if(modstart_config('Blog_ContactWeibo'))
        <div class="ub-pair">
            <div class="name">微博</div>
            <div class="value">
                <a class="tw-text-gray-600" href="{{modstart_config('Blog_ContactWeibo')}}" target="_blank">{{modstart_config('Blog_ContactWeibo')}}</a>
            </div>
        </div>
        @endif
        @if(modstart_config('Blog_ContactWechat'))
        <div class="ub-pair">
            <div class="name">微信</div>
            <div class="value">
                {{modstart_config('Blog_ContactWechat')}}
            </div>
        </div>
        @endif
    </div>
</div>

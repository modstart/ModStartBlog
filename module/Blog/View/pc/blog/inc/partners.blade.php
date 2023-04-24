<div class="ub-content-box margin-bottom">
    <div class="tw-p-3">
        <div class="tw-text-lg">
            <i class="iconfont icon-link"></i>
            友情链接
        </div>
        <div class="tw-mt-4">
            <div class="row">
                @foreach(MPartner::all('Blog') as $p)
                    <div class="col-6">
                        <a href="{{$p['link']}}" target="_blank"
                           class="hover:tw-shadow tw-text-center ub-content-block tw-block tw-rounded tw-leading-10 tw-mb-3 tw-px-2 tw-truncate">
                            {{$p['title']}}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

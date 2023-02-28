<div class="tw-p-6 margin-top tw-bg-white tw-rounded">
    <div class="tw-text-lg">
        <i class="iconfont icon-link ub-text-primary"></i>
        友情链接
    </div>
    <div class="tw-mt-4">
        <div class="row">
            @foreach(MPartner::all('home') as $p)
                <div class="col-6">
                    <a href="{{$p['link']}}" target="_blank"
                       class="hover:tw-shadow tw-text-center tw-text-gray-600 tw-block tw-bg-gray-100 tw-rounded tw-leading-10 tw-mb-3 tw-px-2 tw-truncate">
                        {{$p['title']}}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="ub-content-box margin-bottom">
    <div class="tw-p-3">
        <div class="tw-text-lg">
            <i class="iconfont icon-category"></i>
            分类
        </div>
        <div class="tw-mt-4">
            <div class="row">
                @foreach(\MBlog::categoryTree() as $t)
                    <div class="col-6">
                        <a href="{{modstart_web_url('blogs',['categoryId'=>$t['id']])}}"
                           class="hover:tw -shadow btn btn-block btn-round tw-mb-3 tw-px-2 tw-truncate @if(!empty($category)&&$category['id']==$t['id']) btn-primary @endif">
                            <i class="iconfont icon-angle-right"></i>
                            {{$t['title']}}
                            （{{$t['blogCount']?$t['blogCount']:0}}）
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

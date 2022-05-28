<div class="tw-p-6 margin-top tw-bg-white tw-rounded">
    <div class="tw-text-lg">
        <i class="iconfont icon-category"></i>
        分类
    </div>
    <div class="tw-mt-4">
        <div class="row">
            @foreach(\MBlog::categoryTree() as $t)
                <div class="col-6">
                    <a href="{{modstart_web_url('blogs',['categoryId'=>$t['id']])}}"
                       class="tw-text-gray-600 tw-block tw-bg-gray-100 tw-rounded tw-leading-10 tw-mb-3 tw-px-2 @if(!empty($category)&&$category['id']==$t['id']) ub-bg-primary ub-text-white @endif">
                        <i class="iconfont icon-angle-right ub-text-muted"></i>
                        {{$t['title']}}（{{$t['blogCount']?$t['blogCount']:0}}）
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

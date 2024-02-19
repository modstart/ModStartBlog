@if(\ModStart\Admin\Auth\Admin::isLogin())
    <div class="ub-content-box margin-bottom">
        <div class="tw-p-3">
            <div class="tw-text-lg">
                <i class="iconfont icon-user-o"></i>
                管理专区
            </div>
            <div class="tw-mt-4">
                <div class="row">
                    <div class="col-6">
                        <a href="javascript:;" class="btn btn-round btn-block"
                           data-dialog-width="90%" data-dialog-height="90%"
                           data-dialog-request="{{modstart_admin_url('blog/blog/add',['from'=>'front'])}}"
                        >
                            <i class="iconfont icon-plus"></i>
                            发布博客
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{modstart_admin_url('')}}" class="btn btn-round btn-block">
                            <i class="iconfont icon-link"></i>
                            后台管理
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

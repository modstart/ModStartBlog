@extends('module::Vendor.View.mail.frame')

@section('pageTitle'){{$title}}@endsection

@section('bodyContent')
    @if(is_array($content))
        <table class="ub-email-table">
            @foreach($content as $name=>$value)
                <tr>
                    <td width="140"><b>{{$name}}</b></td>
                    <td style="word-break:break-all;">{{$value}}</td>
                </tr>
            @endforeach
        </table>
    @else
        {!! $content !!}
    @endif
    @if(!empty($param['viewUrl']) || !empty($param['processUrl']))
        <div style="border-top:1px solid #EEE;padding:20px 0 0 0;margin-top:20px;">
            @if(!empty($param['viewUrl']))
                <div>
                    <a href="{{$param['viewUrl']}}" style="display:inline-block;background:{{modstart_config('sitePrimaryColor','#333')}};color:#FFF;padding:5px 20px;border-radius:5px;text-decoration:none;" target="_blank">
                        {{empty($param['viewText'])?'查看内容':$param['viewText']}}
                    </a>
                </div>
                <div style="color:#999;text-align:left;padding-top:10px;font-size:12px;">
                    如点击{{empty($param['viewText'])?'查看内容':$param['viewText']}}无反应，请复制下面链接在浏览器打开：
                </div>
                <div style="color:#999;text-align:left;padding-top:5px;font-size:12px;">
                    <a href="{{$param['viewUrl']}}" style="color:#999;">{{$param['viewUrl']}}</a>
                </div>
            @endif
            @if(!empty($param['processUrl']))
                <div>
                    <a href="{{$param['processUrl']}}" style="display:inline-block;background:{{modstart_config('sitePrimaryColor','#333')}};color:#FFF;padding:5px 20px;border-radius:5px;text-decoration:none;" target="_blank">
                        {{empty($param['processText'])?'立即处理':$param['processText']}}
                    </a>
                </div>
                <div style="color:#999;text-align:left;padding-top:10px;font-size:12px;">
                    如点击{{empty($param['processText'])?'立即处理':$param['processText']}}无反应，请复制下面链接在浏览器打开：
                </div>
                <div style="color:#999;text-align:left;padding-top:5px;font-size:12px;">
                    <a href="{{$param['processUrl']}}" style="color:#999;">{{$param['processUrl']}}</a>
                </div>
            @endif
        </div>
    @endif
@endsection

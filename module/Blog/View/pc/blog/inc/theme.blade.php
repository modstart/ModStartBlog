@if(\Module\Blog\Util\BlogThemeUtil::isDarkTime())
    @section('htmlProperties') data-theme="dark" @endsection
@elseif(\Module\Blog\Util\BlogThemeUtil::isDarkAuto())
    @section('htmlProperties') data-theme="auto" @endsection
@endif
{!! ModStart::css('vendor/Blog/style/basic.css') !!}

@if(\Module\Blog\Util\BlogThemeUtil::isDarkTime())
    @section('htmlProperties')data-theme="dark"@endsection
@elseif(\Module\Blog\Util\BlogThemeUtil::isDarkAuto())
    {!! \ModStart\ModStart::css('vendor/Blog/style/prefers-color-scheme.css') !!}
@endif

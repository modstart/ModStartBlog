<footer class="ub-footer">
    <div class="ub-container">
        <div class="line"></div>
        <div class="nav">
            {!! \Module\Nav\View\NavView::position('foot') !!}
        </div>
        <div class="copyright">
            <a href="http://beian.miit.gov.cn" target="_blank">
                {{modstart_config('siteBeian','[网站备案信息]')}}
            </a>
            @if(modstart_config('siteBeianGonganText'))
                <a href="{{modstart_config('siteBeianGonganLink')}}" target="_blank">
                    <img src="@asset('vendor/Site/image/gongan.png')" />
                    {{modstart_config('siteBeianGonganText')}}
                </a>
            @endif
            &copy;{{modstart_config('siteDomain')}}
        </div>
    </div>
</footer>

<footer class="ub-footer">
    <div class="ub-container">
        <div class="line"></div>
        <div class="nav">
            {!! \Module\Nav\Render\NavRender::position('foot') !!}
        </div>
        <div class="copyright">
            <a href="http://beian.miit.gov.cn" target="_blank">{{modstart_config('siteBeian','[网站备案信息]')}}</a>
            &copy;{{modstart_config('siteDomain')}}
        </div>
    </div>
</footer>

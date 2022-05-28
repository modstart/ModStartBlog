<?php

namespace ModStart\Misc\Captcha;

use Illuminate\Support\Facades\Facade;


class CaptchaFacade extends Facade {

    
    protected static function getFacadeAccessor() { return 'captcha'; }

}

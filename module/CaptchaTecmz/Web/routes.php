<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'captcha_tecmz/verify', 'IndexController@verify');

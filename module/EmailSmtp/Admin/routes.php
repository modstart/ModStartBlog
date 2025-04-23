<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'email_smtp/config/setting', 'ConfigController@setting');
$router->match(['get', 'post'], 'email_smtp/config/test', 'ConfigController@test');




<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'notifier_email/config/setting', 'ConfigController@setting');




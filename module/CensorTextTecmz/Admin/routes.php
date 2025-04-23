<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'censor_text_tecmz/config', 'ConfigController@index');

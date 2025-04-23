<?php

/* @var \Illuminate\Routing\Router $router */

$router->match(['get', 'post'], 'censor_image_tecmz/config', 'ConfigController@index');

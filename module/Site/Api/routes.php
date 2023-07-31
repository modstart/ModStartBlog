<?php


$router->group([
    'middleware' => [
    ],
], function () use ($router) {

    $router->match(['post'], 'site/contact', 'SiteController@contact');

});

<?php


$middleware = [];
if (class_exists(\Module\Member\Middleware\WebAuthMiddleware::class)) {
    $middleware[] = \Module\Member\Middleware\WebAuthMiddleware::class;
}
$router->group([
    'middleware' => $middleware,
], function () use ($router) {
    $router->match(['get'], 'blog', 'IndexController@index');

    $router->match(['get'], 'blog/about', 'AboutController@index');
    $router->match(['get'], 'blog/message', 'MessageController@index');
    $router->match(['get'], 'blog/tags', 'TagsController@index');

    $router->match(['get'], 'blogs', 'BlogController@index');
    $router->match(['get'], 'blog/{id}', 'BlogController@show');

});








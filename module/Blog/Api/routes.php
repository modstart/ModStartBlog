<?php

$middleware = [];
if (class_exists(\Module\Member\Middleware\ApiAuthMiddleware::class)) {
    $middleware[] = \Module\Member\Middleware\ApiAuthMiddleware::class;
}
$router->group([
    'middleware' => $middleware,
], function () use ($router) {

    $router->match(['post'], 'blog/paginate', 'BlogController@paginate');
    $router->match(['post'], 'blog/get', 'BlogController@get');
    $router->match(['post'], 'blog/comment/add', 'CommentController@add');
    $router->match(['post'], 'blog/message/paginate', 'MessageController@paginate');
    $router->match(['post'], 'blog/message/add', 'MessageController@add');
    $router->match(['post'], 'blog/tags/all', 'TagsController@all');

});

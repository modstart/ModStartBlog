<?php
/* @var \Illuminate\Routing\Router $router */
$middleware = [];
if (file_exists(base_path('module/Member/Middleware/ApiAuthMiddleware.php'))) {
    $middleware[] = \Module\Member\Middleware\ApiAuthMiddleware::class;
}
$router->group([
    'middleware' => $middleware,
], function () use ($router) {

    $router->match(['post'], 'blog/paginate', 'BlogController@paginate');
    $router->match(['post'], 'blog/get', 'BlogController@get');
    $router->match(['post'], 'blog/visit_password_verify', 'BlogController@visitPasswordVerify');
    $router->match(['post'], 'blog/comment/add', 'CommentController@add');
    $router->match(['post'], 'blog/message/paginate', 'MessageController@paginate');
    $router->match(['post'], 'blog/message/add', 'MessageController@add');
    $router->match(['post'], 'blog/tags/all', 'TagsController@all');

});

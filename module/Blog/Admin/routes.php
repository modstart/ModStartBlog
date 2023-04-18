<?php



Route::match(['get', 'post'], 'blog/config', 'ConfigController@index');
Route::match(['get', 'post'], 'blog/config/about', 'ConfigController@about');

Route::match(['get', 'post'], 'blog/message', 'BlogMessageController@index');
Route::match(['get', 'post'], 'blog/message/add', 'BlogMessageController@add');
Route::match(['get', 'post'], 'blog/message/edit', 'BlogMessageController@edit');
Route::match(['post'], 'blog/message/delete', 'BlogMessageController@delete');
Route::match(['get'], 'blog/message/show', 'BlogMessageController@show');
Route::match(['get', 'post'], 'blog/message/verify_pass', 'BlogMessageController@verifyPass');
Route::match(['get', 'post'], 'blog/message/verify_reject', 'BlogMessageController@verifyReject');

Route::match(['get', 'post'], 'blog/blog', 'BlogController@index');
Route::match(['get', 'post'], 'blog/blog/add', 'BlogController@add');
Route::match(['get', 'post'], 'blog/blog/edit', 'BlogController@edit');
Route::match(['post'], 'blog/blog/delete', 'BlogController@delete');
Route::match(['get'], 'blog/blog/show', 'BlogController@show');

Route::match(['get', 'post'], 'blog/comment', 'BlogCommentController@index');
Route::match(['get', 'post'], 'blog/comment/add', 'BlogCommentController@add');
Route::match(['get', 'post'], 'blog/comment/edit', 'BlogCommentController@edit');
Route::match(['post'], 'blog/comment/delete', 'BlogCommentController@delete');
Route::match(['get'], 'blog/comment/show', 'BlogCommentController@show');
Route::match(['get', 'post'], 'blog/comment/verify_pass', 'BlogCommentController@verifyPass');
Route::match(['get', 'post'], 'blog/comment/verify_reject', 'BlogCommentController@verifyReject');

Route::match(['get', 'post'], 'blog/category', 'BlogCategoryController@index');
Route::match(['get', 'post'], 'blog/category/add', 'BlogCategoryController@add');
Route::match(['get', 'post'], 'blog/category/edit', 'BlogCategoryController@edit');
Route::match(['post'], 'blog/category/delete', 'BlogCategoryController@delete');
Route::match(['get'], 'blog/category/show', 'BlogCategoryController@show');
Route::match(['post'], 'blog/category/sort', 'BlogCategoryController@sort');

Route::match(['get', 'post'], 'blog/super_search', 'BlogSuperSearchController@index');


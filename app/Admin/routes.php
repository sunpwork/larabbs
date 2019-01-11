<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->group(['prefix' => 'manage'], function (Router $router) {
        $router->resource('users', 'UsersController');

        $router->group(['prefix' => 'contents'], function (Router $router) {
            $router->resource('categories', 'CategoriesController');
            $router->resource('topics', 'TopicsController');
            $router->resource('replies', 'RepliesController');
        });

        $router->group(['prefix' => 'settings'], function (Router $router) {
            $router->resource('site_settings', 'SiteSettingsController', ['only' => ['edit', 'update']]);
            $router->resource('links', 'LinksController');
        });

    });

});

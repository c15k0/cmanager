<?php

use App\Admin\Controllers\CampaignController;
use App\Admin\Controllers\ContactController;
use App\Admin\Controllers\CustomerController;
use App\Admin\Controllers\GroupController;
use App\Admin\Controllers\ReceiverController;
use App\Admin\Controllers\TemplateController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use OpenAdmin\Admin\Facades\Admin;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('customers', CustomerController::class);
    $router->resource('contacts', ContactController::class);
    $router->resource('groups', GroupController::class);
    $router->resource('templates', TemplateController::class);
    $router->resource('campaigns', CampaignController::class);
    $router->resource('receivers', ReceiverController::class);

});

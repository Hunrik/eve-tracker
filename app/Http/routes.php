<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
/**
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/prices','MarketDataController@index');
});
Route::get('/test',function(){
   dd(uniqid());
});

Route::group(['middleware' => 'api'],function() {
    Route::get('/items',function(){
        $name = Request::input('term');
        return App\Items::where('name','LIKE',$name.'%')->get();
    });
    Route::get('/api/blueprint', function() {
        $name = Request::input('term');
        return App\Blueprints::where('typeName','LIKE',$name.'%')->get();
    });
});
Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/','DashboardController@show');

    Route::get('/market', 'MarketDataController@emdr');
    Route::get('/comp', 'MarketDataController@comp');
    
    Route::resource('/api/statistic/profit', 'ProfitStatisticsController', ['only' => [
        'index', 'show'
    ]]);
    Route::resource('/job', 'JobController', ['only' => [
        'index', 'show', 'update'
    ]]);
    Route::resource('/manufacturing','ManufacturingController', [
        'index', 'store'
    ]);
    /**
     * Settings Page routes
     *
     * TODO Implement ApiKey viewer
     * TODO Implement Security
     * TODO Implement Key delete
     */
    Route::get('/settings','SettingsController@index');
    Route::post('/user/addApiKey','SettingsController@addApiKey');
    Route::post('/user/getChars','SettingsController@getChars');
    Route::get('/user/setAccess/{key}','SettingsController@setAccess');

    //Route::get('/', 'HomeController@index');
    Route::get('/dashboard', 'DashboardController@index');

    /**
     * TODO Cretae filter
     */
    Route::get('/journal','JournalController@index');
    Route::get('/journal/update','JournalController@update');

    /**
     * Adding jobs
     */
    Route::get('/orders','JobProcessorController@index');
    Route::post('/api/addJob','JobProcessorController@create');
    Route::get('/api/order/{id}','JobProcessorController@show');
    Route::get('/api/connectOrders','JobProcessorController@connectOrders');
    Route::get('/prices/update','MarketDataController@update');




    /**
     * Special Routes
     */
    Route::get('/trigger/fix','HomeController@jobFix');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
//Route::get('test', 'api\v1\ConversationController@test');

Route::group(['middleware' => ['apiDataLogger']], function() {
Route::group(['middleware' => ['isAppUser']], function (Router $router) {
        Route::group(array('prefix' => 'v1'), function() {
            Route::post('password/email', 'api\v1\ForgotPasswordController@sendResetApiLinkEmail')->name('forgotpassword');
            Route::middleware('auth:api')->group(function () {
                Route::resource('user', 'api\v1\UserController');
                Route::post('updatedevicetoken', 'api\v1\UserController@updateDeviceToken')->name('update.devicetoken');
                Route::post('user/logout', 'api\v1\UserController@logout')->name('logout');
            });

            Route::post('user/login', 'api\v1\UserController@login')->name('api.login');
            Route::post('user/register', 'api\v1\UserController@register')->name('api.register');

        });
    });
});

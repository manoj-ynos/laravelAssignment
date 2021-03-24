<?php
Route::get('/', function() {
    return redirect(route('admin.dashboard'));
});

Route::get('home', function() {
    return redirect(route('admin.dashboard'));
});

//Route::post('login/sendlink', 'Auth\LoginController@sendlink');
Route::get('register/{token}', 'Auth\RegisterController@check_user');

Route::post('status_change/{id}', 'UserController@status_change')->name('status_change');
Route::get('profile_image', 'UserController@profile_image')->name('admin.profile_image');

Route::post('register_update', 'Auth\RegisterController@update')->name('register_update');

Route::name('admin.')->prefix('admin')->middleware('auth')->group(function() {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
	
    Route::get('users/roles', 'UserController@roles')->name('users.roles');
	

    Route::resource('users', 'UserController', [
        'names' => [
            'index' => 'users'
        ]
    ]);
	
	
});

Route::middleware('auth')->get('logout', function() {
    Auth::logout();
    return redirect(route('login'))->withInfo('You have successfully logged out!');
})->name('logout');

Auth::routes(['verify' => true]);

Route::name('js.')->group(function() {
    Route::get('dynamic.js', 'JsController@dynamic')->name('dynamic');
});

// Get authenticated user
Route::get('users/auth', function() {
    return response()->json(['user' => Auth::check() ? Auth::user() : false]);
});

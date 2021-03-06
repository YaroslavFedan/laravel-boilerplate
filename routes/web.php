<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');


//namespace - заменяет все пути к контроллеру, типа \Admin\UsersController на просто UsersController
//prefix - заменяет все пути в именах роутов, типа /admin/users  на просто /users
//name - добавляет ко всем путям к вью файлам, путь до директории admin.
Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware(['auth','2fa','can:manage-users'])->group(function (){
    Route::resource('/users','UsersController');//,['except'=>['show','create','store']]
});


Route::get('/2fa','PasswordSecurityController@show2faForm');
Route::post('/generate2faSecret','PasswordSecurityController@generate2faSecret')->name('generate2faSecret');
Route::post('/2fa','PasswordSecurityController@enable2fa')->name('enable2fa');
Route::post('/disable2fa','PasswordSecurityController@disable2fa')->name('disable2fa');


Route::post('/verify2FA','HomeController@verify2FA');
Route::post('/2faVerify', function () {
    return redirect(request()->session()->get('_previous')['url']);
})->name('2faVerify')->middleware('2fa');
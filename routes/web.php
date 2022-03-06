<?php

use Illuminate\Support\Facades\Route;
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
Route::view('/','welcome');
Auth::routes();

Route::view('/login','login')->name('login')->middleware('guest');

Route::post('/post-login',function(){
    $credentials = request()->only('email','password');

    if(Auth::attempt($credentials)){
        request()->session()->regenerate();
        return redirect('dashboard');
    }

    return redirect('login');
});
Route::view('dashboard','dashboard')->middleware('auth');
Route::get('/evidence/landing','App\Http\Controllers\EvidenceController@landing');
Route::get('/evidence/show/{id}','App\Http\Controllers\EvidenceController@show');
Route::group(['middleware' => 'auth'], function(){

    /** ruta para hacer la petición por guzzel */
    Route::get('/api/{url}','App\Http\Controllers\GuzzelController@getRequest');

    Route::get('/logout',function(){
        \Session::flush();
        
        \Auth::logout();

        return redirect('login');
    });
    Route::get('/users/index','App\Http\Controllers\UsersController@index')->name('users');
    Route::get('/users/dataTableUsers','App\Http\Controllers\UsersController@dataTable')->name('dataTableUsers');
    Route::get('/users/create','App\Http\Controllers\UsersController@create')->name('create.users');
    Route::post('/users/','App\Http\Controllers\UsersController@store')->name('userStore');
    Route::get('/users/edit/{id}','App\Http\Controllers\UsersController@edit');
    Route::post('/users/edit/','App\Http\Controllers\UsersController@update');
    Route::post('/users/delete','App\Http\Controllers\UsersController@destroy');

    Route::get('/categories/index','App\Http\Controllers\CategoriesController@index')->name('Categories');
    Route::get('/categories/dataTableCategories','App\Http\Controllers\CategoriesController@dataTable')->name('dataTableCategories');
    Route::get('/categories/create','App\Http\Controllers\CategoriesController@create')->name('create.Categories');
    Route::post('/categories/','App\Http\Controllers\CategoriesController@store')->name('Categoriestore');
    Route::get('/categories/edit/{id}','App\Http\Controllers\CategoriesController@edit');
    Route::post('/categories/edit/','App\Http\Controllers\CategoriesController@update');
    Route::post('/categories/delete','App\Http\Controllers\CategoriesController@destroy');

    Route::get('/evidence/index','App\Http\Controllers\EvidenceController@index')->name('Evidence');
    Route::get('/evidence/dataTableEvidence','App\Http\Controllers\EvidenceController@dataTable')->name('dataTableEvidence');
    Route::get('/evidence/create','App\Http\Controllers\EvidenceController@create')->name('create.Evidence');
    Route::post('/evidence/','App\Http\Controllers\EvidenceController@store')->name('Evidencetore');
    Route::get('/evidence/edit/{id}','App\Http\Controllers\EvidenceController@edit');
    Route::post('/evidence/edit/','App\Http\Controllers\EvidenceController@update');
    Route::post('/evidence/delete','App\Http\Controllers\EvidenceController@destroy');
    


    Route::get('/images/{id}','App\Http\Controllers\EvidenceImageController@getEvidence');
    Route::post('/images/store','App\Http\Controllers\EvidenceImageController@store')->name('evidenceImagesStore');
    Route::post('/images/update/status','App\Http\Controllers\EvidenceImageController@updateStatus');
    Route::post('/images/destroy/','App\Http\Controllers\EvidenceImageController@destroy');
});
/* 
Route::view('logout','logout');

Route::post('/post-login',function(){
    $credentials = request()->only('email','password');

    if(Auth::attempt($credentials)){
        request()->session()->regenerate();
        return redirect('dashboard');
    }

    return redirect('login');
}); */


/* 
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); */

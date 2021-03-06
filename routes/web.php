<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes(['register' => false]);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/my-tokens', [HomeController::class, 'getTokens'])->name('personal-tokens');
Route::get('/home/my-clients', [HomeController::class, 'getClients'])->name('personal-clients');
Route::get('/home/my-authorized-clients', [HomeController::class, 'getAuthorizedClients'])
    ->name('authorized-clients');


Route::get('/', function (){
    return view('welcome');
})->middleware('guest');

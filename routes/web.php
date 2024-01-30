<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('auth/redirect', function () {
    
    return Socialite::driver('imgur')->redirect();
})->name('auth.redirect');

Route::get('auth/callback', function () {
    $imaguruser = Socialite::driver('imgur')->user();

    $user = User::updateOrCreate([
        'imagur_id' => $imaguruser->id
    ], [
        'name' => $imaguruser->name,
        'email' => $imaguruser->email,
        'imagur_token' => $imaguruser->access_token,
        'imagur_refresh_token' => $imaguruser->refresh_token,
    ]);
    
    Auth::login($user);

    return redirect('/dashboard');

})->name('auth.callback');

require __DIR__.'/auth.php';

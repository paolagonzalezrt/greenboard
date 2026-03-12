<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Página inicial → LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('auth.login');
});

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return "Login correcto ✔";
    }

    return back()->withErrors([
        'email' => 'Credenciales incorrectas',
    ]);

});

/*
|--------------------------------------------------------------------------
| REGISTRO
|--------------------------------------------------------------------------
*/

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login');

});

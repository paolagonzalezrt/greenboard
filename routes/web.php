<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (WELCOME)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Modelo: Tip::all();
    $tips = [
        [
            'category' => 'Zero Waste',
            'user' => '@eco_felix',
            'title' => 'Mastering the Art of Backyard Composting',
            'description' => 'Learn how to turn your kitchen scraps into nutrient-rich soil gold.',
            'likes' => 412, 'comments' => 24,
            'image' => 'https://picsum.photos/id/10/400/300',
            'avatar' => 'https://i.pravatar.cc/150?u=felix'
        ],
        [
            'category' => 'Energy',
            'user' => '@solar_pro',
            'title' => "Switching to Solar: A Beginner's Guide",
            'description' => 'Navigate the financial landscape of renewable energy easily.',
            'likes' => 254, 'comments' => 12,
            'image' => 'https://picsum.photos/id/20/400/300',
            'avatar' => 'https://i.pravatar.cc/150?u=solar'
        ],
          [
            'category' => 'Zero Waste',
            'user' => '@eco_felix',
            'title' => 'Mastering the Art of Backyard Composting',
            'description' => 'Learn how to turn your kitchen scraps into nutrient-rich soil gold.',
            'likes' => 412, 'comments' => 24,
            'image' => 'https://picsum.photos/id/10/400/300',
            'avatar' => 'https://i.pravatar.cc/150?u=felix'
        ],
        [
            'category' => 'Energy',
            'user' => '@solar_pro',
            'title' => "Switching to Solar: A Beginner's Guide",
            'description' => 'Navigate the financial landscape of renewable energy easily.',
            'likes' => 254, 'comments' => 12,
            'image' => 'https://picsum.photos/id/20/400/300',
            'avatar' => 'https://i.pravatar.cc/150?u=solar'
        ],
        [
            'category' => 'Energy',
            'user' => '@solar_pro',
            'title' => "Switching to Solar: A Beginner's Guide",
            'description' => 'Navigate the financial landscape of renewable energy easily.',
            'likes' => 254, 'comments' => 12,
            'image' => 'https://picsum.photos/id/20/400/300',
            'avatar' => 'https://i.pravatar.cc/150?u=solar'
        ],
          [
            'category' => 'Zero Waste',
            'user' => '@eco_felix',
            'title' => 'Mastering the Art of Backyard Composting',
            'description' => 'Learn how to turn your kitchen scraps into nutrient-rich soil gold.',
            'likes' => 412, 'comments' => 24,
            'image' => 'https://picsum.photos/id/10/400/300',
            'avatar' => 'https://i.pravatar.cc/150?u=felix'
        ],
        [
            'category' => 'Energy',
            'user' => '@solar_pro',
            'title' => "Switching to Solar: A Beginner's Guide",
            'description' => 'Navigate the financial landscape of renewable energy easily.',
            'likes' => 254, 'comments' => 12,
            'image' => 'https://picsum.photos/id/20/400/300',
            'avatar' => 'https://i.pravatar.cc/150?u=solar'
        ],
          [
            'category' => 'Zero Waste',
            'user' => '@eco_felix',
            'title' => 'Mastering the Art of Backyard Composting',
            'description' => 'Learn how to turn your kitchen scraps into nutrient-rich soil gold.',
            'likes' => 412, 'comments' => 24,
            'image' => 'https://picsum.photos/id/10/400/300',
            'avatar' => 'https://i.pravatar.cc/150?u=felix'
        ],
        
    ];

    return view('welcome', compact('tips'));
})->name('home');

// ABOUT US
Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

// PRIVACY POLICY
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

// LOGIN
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        // Redirige al inicio (welcome) con sesión iniciada
        return redirect()->intended('/'); 
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden.',
    ])->onlyInput('email');
});

// REGISTRO
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'Cuenta creada con éxito');
});

// LOGOUT (IMPORTANTE: Laravel recomienda que sea POST por seguridad)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| IDIOMA
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'de'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
});

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (WELCOME)
|--------------------------------------------------------------------------
*/

Route::get('/', [TipController::class, 'index'])->name('home');

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

// DASHBOARD (Protected Route - Solo para usuarios autenticados)
Route::get('/dashboard', [TipController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// FOLLOWING (Protected Route - Solo para usuarios autenticados)
Route::get('/following', [TipController::class, 'following'])->middleware('auth')->name('following');

// SAVED (Protected Route - Solo para usuarios autenticados)
Route::get('/saved', [TipController::class, 'saved'])->middleware('auth')->name('saved');

// TIPS - Create and View Posts
Route::get('/tips/create', [TipController::class, 'create'])->middleware('auth')->name('tips.create');
Route::post('/tips', [TipController::class, 'store'])->middleware('auth')->name('tips.store');
Route::get('/tips/{tip}', [TipController::class, 'show'])->name('tips.show');
Route::delete('/tips/{tip}', [TipController::class, 'destroy'])->middleware('auth')->name('tips.destroy');

// REPORTS - Report Tips
Route::post('/tips/{tip}/report', [\App\Http\Controllers\ReportController::class, 'store'])->middleware('auth')->name('tips.report');

// COMMENTS
Route::post('/tips/{tip}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->middleware('auth')->name('comments.store');
Route::post('/comments/{comment}/reply', [\App\Http\Controllers\CommentController::class, 'reply'])->middleware('auth')->name('comments.reply');

// LIKES
Route::post('/tips/{tip}/like', [\App\Http\Controllers\LikeController::class, 'toggle'])->middleware('auth')->name('tips.like');

// BOOKMARKS
Route::post('/tips/{tip}/bookmark', [\App\Http\Controllers\BookmarkController::class, 'toggle'])->middleware('auth')->name('tips.bookmark');

// FOLLOWS
Route::post('/users/{user}/follow', [\App\Http\Controllers\FollowController::class, 'toggle'])->middleware('auth')->name('users.follow');
Route::get('/users/{user}/followers', [\App\Http\Controllers\FollowController::class, 'followers'])->middleware('auth')->name('users.followers');
Route::get('/users/{user}/following', [\App\Http\Controllers\FollowController::class, 'following'])->middleware('auth')->name('users.following');
Route::delete('/users/{follower}/remove-follower', [\App\Http\Controllers\FollowController::class, 'removeFollower'])->middleware('auth')->name('users.removeFollower');

// PROFILE (Protected Route - Solo para usuarios autenticados)
Route::get('/profile', [TipController::class, 'profile'])->middleware('auth')->name('profile');

// PROFILE EDIT (Protected Route - Editar perfil del usuario autenticado)
Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::patch('/profile/update', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->middleware('auth')->name('profile.updatePassword');
Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware('auth')->name('profile.destroy');

// USER PROFILE (Public Route - Ver perfil de cualquier usuario)
Route::get('/users/{user}', [TipController::class, 'showUserProfile'])->name('users.show');

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
        // Redirige al dashboard después de iniciar sesión
        return redirect()->intended(route('dashboard')); 
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
| RUTAS DE ADMINISTRACIÓN
|--------------------------------------------------------------------------
*/

// Admin - Gestión de tips reportados
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reported-tips', [AdminController::class, 'reportedTips'])->name('reported-tips');
    Route::delete('/tips/{tip}', [AdminController::class, 'deleteTip'])->name('tips.delete');
    Route::patch('/reports/{report}/status', [AdminController::class, 'updateReportStatus'])->name('reports.update-status');
});

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

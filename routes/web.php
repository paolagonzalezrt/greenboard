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
Route::post('/comments/{comment}/report', [\App\Http\Controllers\ReportController::class, 'storeCommentReport'])->middleware('auth')->name('comments.report');

// COMMENTS
Route::post('/tips/{tip}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->middleware('auth')->name('comments.store');
Route::post('/comments/{comment}/reply', [\App\Http\Controllers\CommentController::class, 'reply'])->middleware('auth')->name('comments.reply');
Route::post('/comments/{comment}/like', [\App\Http\Controllers\CommentController::class, 'like'])->middleware('auth')->name('comments.like');
Route::delete('/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->middleware('auth')->name('comments.destroy');

// LIKES
Route::post('/tips/{tip}/like', [\App\Http\Controllers\LikeController::class, 'toggle'])->middleware('auth')->name('tips.like');

// BOOKMARKS
Route::post('/tips/{tip}/bookmark', [\App\Http\Controllers\BookmarkController::class, 'toggle'])->middleware('auth')->name('tips.bookmark');

// NOTIFICATIONS
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('markAsRead');
    Route::post('/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
});

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
Route::patch('/profile/email', [ProfileController::class, 'updateEmail'])->middleware('auth')->name('profile.updateEmail');
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
    ], [
        'email.required' => __('login.error_email_required'),
        'email.email' => __('login.error_email_invalid'),
        'password.required' => __('login.error_password_required'),
    ]);

    // Verificar si el usuario existe
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()
            ->withErrors(['email' => __('login.error_email_not_registered')])
            ->onlyInput('email');
    }

    // Intentar autenticar
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard')); 
    }

    // Si llegamos aquí, el email existe pero la contraseña es incorrecta
    return back()
        ->withErrors(['password' => __('login.error_password_incorrect')])
        ->onlyInput('email');
});

// REGISTRO
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'password_confirmation' => 'required|same:password',
    ], [
        'name.required' => __('register.error_name_required'),
        'name.string' => 'El nombre debe ser texto',
        'name.max' => 'El nombre no puede exceder 255 caracteres',
        'email.required' => __('register.error_email_required'),
        'email.email' => __('register.error_email_invalid'),
        'email.unique' => __('register.error_email_exists'),
        'password.required' => __('register.error_password_required'),
        'password.min' => __('register.error_password_min'),
        'password_confirmation.required' => __('register.error_password_required'),
        'password_confirmation.same' => __('register.error_password_mismatch'),
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Autenticar al usuario automáticamente
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Cuenta creada con éxito');
    } catch (\Exception $e) {
        return back()
            ->withErrors(['error' => 'Ocurrió un error al crear la cuenta. Intenta nuevamente.'])
            ->withInput();
    }
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
    Route::delete('/comments/{comment}', [AdminController::class, 'deleteComment'])->name('comments.delete');
    Route::delete('/tips/{tip}/dismiss-all', [AdminController::class, 'dismissAllReportsForTip'])->name('tips.dismiss-all');
    Route::delete('/comments/{comment}/dismiss-all', [AdminController::class, 'dismissAllReportsForComment'])->name('comments.dismiss-all');
    Route::delete('/reports/{report}/dismiss', [AdminController::class, 'dismissReport'])->name('reports.dismiss');
});

/*
|--------------------------------------------------------------------------
| IDIOMA / LOCALIZATION
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\LocaleController;

// Cambiar idioma (GET - para links)
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Cambiar idioma (POST - para AJAX)
Route::post('/locale/switch', [LocaleController::class, 'switchAjax'])->name('locale.switch.ajax');

// Obtener idiomas disponibles (API)
Route::get('/api/locales', [LocaleController::class, 'getAvailableLocales'])->name('locale.available');

// Compatibilidad con ruta anterior
Route::get('/lang/{locale}', [LocaleController::class, 'switch']);

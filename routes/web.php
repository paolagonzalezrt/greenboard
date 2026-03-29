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

// DASHBOARD (Protected Route - Solo para usuarios autenticados)
Route::get('/dashboard', function () {
    $tips = [
        [
            'category' => 'Consumption',
            'user' => 'Ana Green',
            'title' => 'Zero-waste bathroom',
            'description' => '10 simple ways to transition your daily routine to a more sustainable, plastic-free lifestyle.',
            'likes' => '2.4k',
            'comments' => 128,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzoxJGg1H4saBg1uRC1Z2djn9839reyS3_7he0i8zRp9jQ7CLp7n8mx-fAX3Bp_d1lm9gw21fBFD7llRJTloljPsCtNghRbLYnSW3U-h8Tv4qp9QI4OJczFxEkdhGWUdZH6qY6xsfDNn2aJ4W-LkllNLz3-vAXgK3D4w3myKjhxk1_5TsCfHzCZ5mJnfy1ms4irgS1k5SffJ_Xp4_vitUnHeh0Qk3DaOsz16PUZGZbP0NHVEHgkmY-IbeWOFPanwPyEiELpn3QnJNQ',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAeI-3_fdGQV__t8ZyDMMFv0AOT-WhFDlzrLD0kTA5EpfzTczXwpWg1KLW_bddS77WcGgnrCsHc-auoGFLh-4GPsDHmSH1OB7kLlAhNzmhVBL6ImXuXjHQOIDqJIcpLO6AbqGHvHgO6sm8YmJQmSqlMidkL2LphomyBnuaiVFEizBqcVE1vsMMj12JHeQyor57k8_5dAa9fXFHCnhTzMwiYvTg3tJU27SkbsRaXUGSjmln64-1pOWvveCuaPz_gY4lrNBjRSftajxZl'
        ],
        [
            'category' => 'Food',
            'user' => 'Liam Eco',
            'title' => 'Vertical gardening',
            'description' => 'Everything you need to know about growing your own fresh salad directly on your balcony.',
            'likes' => '1.1k',
            'comments' => 42,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDQpJsgo0-MUgYp98ZhfAc53ksGdD3ZAtFMI4NFN8t0ZeUvS9HdouMczpRlGti-G2zMucrOm5H1redW0K1blyJ3AcF4mmNOt2HqnVu40bJiWPrqwLOLRvGhZ9dO4HTEgxg_0z_iq3RgtIAU6dUP-3Na4P6EM6x-eZBUGN6tcIFPnv3VoZ7BnhKFahPxz7rx3fD666DMF5GR3xJrnajsvDpQEixvotL23ZIt-zDpF16ADtfKAT1JCe824oOE3tS9vwDhuoxeLZyFULFO',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBeAKuHHRz0Nz3a05jgiDaiXs2CYbTZKjFh5L_KUiVXRNXVzQFMc1wOWQ47TMszYr8lq0ct9U0-GBlKhIZRqXi04gT7VxuPT6Yi_bxlSbTFKBTFEGak5mKr8jUSPyYmdyBn460E-TU6xtyccpm_5vLEK3lWYX4q0wZYvRKWBS-p_2g32DF9Hav2djuIrTn5WGePtarGO7REhas4Jlx4wy9UhRPcLZQIl3WmGDa-Dn8Kjn_1Cz2IG3MmFYOugwSt3YF-s77L_BdLb4Zw'
        ],
        [
            'category' => 'Zero Waste',
            'user' => 'Eco Warrior',
            'title' => 'Quick Tip: Reusable Coffee Cups',
            'description' => 'Did you know that by bringing your own reusable coffee cup, you can save up to 500 disposable cups per year? Many coffee shops even offer a small discount! Start your sustainable coffee journey today.',
            'likes' => 543,
            'comments' => 28,
            'image' => null,
            'avatar' => 'https://i.pravatar.cc/150?u=ecowarrior'
        ],
        [
            'category' => 'Energy',
            'user' => 'SolarMax',
            'title' => 'Solar efficiency',
            'description' => 'A deep dive into whether solar panels are worth the investment in 2024 and how to maximize output.',
            'likes' => 892,
            'comments' => 56,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCpNIdtFNei9u_BWssoyjMPL403G9kJr8c9r9wn7263TzLjZoJFitKjNXKIzYz_GQh-ILrnY-20yLFpGis0yMb6ISJGqnE9rW19-I7vphkvbvvUMYf0TIUH28KV64PZWn4oprm7UPRL_vtEA2Pe5ANOFAEC5aIBY08RBgVTNT3N4Gppb3edvGq3-0uHQYCCCZK7WTgFl_j_i9798bEuQwAyWMEA7WCFVH5vacf6y5Ic8emoW91S8W209IvzXcUlWKsslkER1_qKCt0h',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD9y59Jh91vTJJ3DgOPYsXnNXTWJ43VMrIeq4TAPnXMKLTeux2PbMsqcAHPm9nJGyPoEqmq83FAByfLmSepCkzuS8rYF-L-UMQm8mOvuEdYR-0cz9x26g0oYT2HGGsOrtge-tQK5XTNFVtaVkP0c_pudOOSHyFDFexVmcOVRVncsESXEY4pTJFGawCmT2mZNiX3u3v1SbkPEhL9Cj_fmFvnUEl__bByPbHFEhSTXQS5oS_8eAq2EWHJUchUKscQBPQNBo19G_2f2ONm'
        ],
        [
            'category' => 'Home',
            'user' => 'Green Home Tips',
            'title' => 'Natural Cleaning Products',
            'description' => 'Transform your cleaning routine with these simple, eco-friendly recipes using ingredients you already have at home. Vinegar, baking soda, and lemon are all you need for a sparkling clean home!',
            'likes' => '1.2k',
            'comments' => 87,
            'image' => null,
            'avatar' => 'https://i.pravatar.cc/150?u=greenhome'
        ],
        [
            'category' => 'Transport',
            'user' => 'Elena Cycle',
            'title' => 'Bike commuting',
            'description' => 'Discover the surprising health and mental benefits of switching to a bicycle for your daily commute.',
            'likes' => '3.8k',
            'comments' => 210,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC6zaMnFCg3KaT5gDXiI7xHTnNHJyyH9_w-VN3EDHyigjm_J5vM3AR4LqfIJdBFsG8v-P8LzpzSlcz23Ydm1LSTA3ktrkUb5oOgnJFrQREebmB9p8RTF7zZ1ukVpJiEVJpar8bIz9rZXYh_YvumJvIgp70JJCYEPOXO3siMd95yPm37p2bRga1CLWc0scNjC7XrjxA47BrwipIjG-Ts9bblBGCvG2lZP0DaN7dsMQv9NUaJoGEptd2dc6PRb4LuivXdNoyMgG9fTra5',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBlpzN7Emouz-aGyvwepWENK_chUNEbxCizxSrGsnq1y0qWDZ9VzPwlDr66WvH2xYnlD6kfmq3Ut53K3999fWE4MccmP63Rx9FcE1YxpsWmjIvgILqJN0nqq7UAIyffrWOZcmkK1b7J1KZa1PFfZAbRTO2x-3w8rKfxij-ok1SmoKfB4wBmLgpT-K9Ka4yiLTCkvW0xcIh5Jd2NloqAMldboS5HHcNDOCo5QUDo2VSI1UgATiwL898sh-3z_Y3Ukf7YBx4Qzx372S1w'
        ],
    ];

    return view('dashboard', compact('tips'));
})->middleware('auth')->name('dashboard');

// FOLLOWING (Protected Route - Solo para usuarios autenticados)
Route::get('/following', function () {
    $tips = [
        [
            'category' => 'Zero Waste',
            'user' => 'Eco Felix',
            'title' => 'DIY Reusable Beeswax Wraps',
            'description' => 'Say goodbye to plastic wrap! Learn how to make your own sustainable food storage.',
            'likes' => '1.5k',
            'comments' => 89,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzoxJGg1H4saBg1uRC1Z2djn9839reyS3_7he0i8zRp9jQ7CLp7n8mx-fAX3Bp_d1lm9gw21fBFD7llRJTloljPsCtNghRbLYnSW3U-h8Tv4qp9QI4OJczFxEkdhGWUdZH6qY6xsfDNn2aJ4W-LkllNLz3-vAXgK3D4w3myKjhxk1_5TsCfHzCZ5mJnfy1ms4irgS1k5SffJ_Xp4_vitUnHeh0Qk3DaOsz16PUZGZbP0NHVEHgkmY-IbeWOFPanwPyEiELpn3QnJNQ',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAeI-3_fdGQV__t8ZyDMMFv0AOT-WhFDlzrLD0kTA5EpfzTczXwpWg1KLW_bddS77WcGgnrCsHc-auoGFLh-4GPsDHmSH1OB7kLlAhNzmhVBL6ImXuXjHQOIDqJIcpLO6AbqGHvHgO6sm8YmJQmSqlMidkL2LphomyBnuaiVFEizBqcVE1vsMMj12JHeQyor57k8_5dAa9fXFHCnhTzMwiYvTg3tJU27SkbsRaXUGSjmln64-1pOWvveCuaPz_gY4lrNBjRSftajxZl'
        ],
        [
            'category' => 'Food',
            'user' => 'Green Kitchen',
            'title' => 'Plant-based meal prep',
            'description' => 'Weekly meal prep ideas that are healthy, sustainable, and budget-friendly.',
            'likes' => '2.1k',
            'comments' => 145,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDQpJsgo0-MUgYp98ZhfAc53ksGdD3ZAtFMI4NFN8t0ZeUvS9HdouMczpRlGti-G2zMucrOm5H1redW0K1blyJ3AcF4mmNOt2HqnVu40bJiWPrqwLOLRvGhZ9dO4HTEgxg_0z_iq3RgtIAU6dUP-3Na4P6EM6x-eZBUGN6tcIFPnv3VoZ7BnhKFahPxz7rx3fD666DMF5GR3xJrnajsvDpQEixvotL23ZIt-zDpF16ADtfKAT1JCe824oOE3tS9vwDhuoxeLZyFULFO',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBeAKuHHRz0Nz3a05jgiDaiXs2CYbTZKjFh5L_KUiVXRNXVzQFMc1wOWQ47TMszYr8lq0ct9U0-GBlKhIZRqXi04gT7VxuPT6Yi_bxlSbTFKBTFEGak5mKr8jUSPyYmdyBn460E-TU6xtyccpm_5vLEK3lWYX4q0wZYvRKWBS-p_2g32DF9Hav2djuIrTn5WGePtarGO7REhas4Jlx4wy9UhRPcLZQIl3WmGDa-Dn8Kjn_1Cz2IG3MmFYOugwSt3YF-s77L_BdLb4Zw'
        ],
        [
            'category' => 'Transport',
            'user' => 'Urban Cyclist',
            'title' => 'Best bike routes in the city',
            'description' => 'Explore the safest and most scenic bike paths for your daily commute.',
            'likes' => '967',
            'comments' => 43,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC6zaMnFCg3KaT5gDXiI7xHTnNHJyyH9_w-VN3EDHyigjm_J5vM3AR4LqfIJdBFsG8v-P8LzpzSlcz23Ydm1LSTA3ktrkUb5oOgnJFrQREebmB9p8RTF7zZ1ukVpJiEVJpar8bIz9rZXYh_YvumJvIgp70JJCYEPOXO3siMd95yPm37p2bRga1CLWc0scNjC7XrjxA47BrwipIjG-Ts9bblBGCvG2lZP0DaN7dsMQv9NUaJoGEptd2dc6PRb4LuivXdNoyMgG9fTra5',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBlpzN7Emouz-aGyvwepWENK_chUNEbxCizxSrGsnq1y0qWDZ9VzPwlDr66WvH2xYnlD6kfmq3Ut53K3999fWE4MccmP63Rx9FcE1YxpsWmjIvgILqJN0nqq7UAIyffrWOZcmkK1b7J1KZa1PFfZAbRTO2x-3w8rKfxij-ok1SmoKfB4wBmLgpT-K9Ka4yiLTCkvW0xcIh5Jd2NloqAMldboS5HHcNDOCo5QUDo2VSI1UgATiwL898sh-3z_Y3Ukf7YBx4Qzx372S1w'
        ],
    ];

    return view('following', compact('tips'));
})->middleware('auth')->name('following');

// SAVED (Protected Route - Solo para usuarios autenticados)
Route::get('/saved', function () {
    $tips = [
        [
            'category' => 'Consumption',
            'user' => 'Ana Green',
            'title' => 'Zero-waste bathroom',
            'description' => '10 simple ways to transition your daily routine to a more sustainable, plastic-free lifestyle.',
            'likes' => '2.4k',
            'comments' => 128,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzoxJGg1H4saBg1uRC1Z2djn9839reyS3_7he0i8zRp9jQ7CLp7n8mx-fAX3Bp_d1lm9gw21fBFD7llRJTloljPsCtNghRbLYnSW3U-h8Tv4qp9QI4OJczFxEkdhGWUdZH6qY6xsfDNn2aJ4W-LkllNLz3-vAXgK3D4w3myKjhxk1_5TsCfHzCZ5mJnfy1ms4irgS1k5SffJ_Xp4_vitUnHeh0Qk3DaOsz16PUZGZbP0NHVEHgkmY-IbeWOFPanwPyEiELpn3QnJNQ',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAeI-3_fdGQV__t8ZyDMMFv0AOT-WhFDlzrLD0kTA5EpfzTczXwpWg1KLW_bddS77WcGgnrCsHc-auoGFLh-4GPsDHmSH1OB7kLlAhNzmhVBL6ImXuXjHQOIDqJIcpLO6AbqGHvHgO6sm8YmJQmSqlMidkL2LphomyBnuaiVFEizBqcVE1vsMMj12JHeQyor57k8_5dAa9fXFHCnhTzMwiYvTg3tJU27SkbsRaXUGSjmln64-1pOWvveCuaPz_gY4lrNBjRSftajxZl'
        ],
        [
            'category' => 'Transport',
            'user' => 'Daily Commuter',
            'title' => 'Carpool Benefits',
            'description' => 'Sharing rides with colleagues not only reduces your carbon footprint but also saves money on gas and parking. Plus, you get to enjoy good company on your commute! Try organizing a carpool group at work.',
            'likes' => 756,
            'comments' => 45,
            'image' => null,
            'avatar' => 'https://i.pravatar.cc/150?u=commuter'
        ],
        [
            'category' => 'Energy',
            'user' => 'SolarMax',
            'title' => 'Solar efficiency',
            'description' => 'A deep dive into whether solar panels are worth the investment in 2024 and how to maximize output.',
            'likes' => 892,
            'comments' => 56,
            'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCpNIdtFNei9u_BWssoyjMPL403G9kJr8c9r9wn7263TzLjZoJFitKjNXKIzYz_GQh-ILrnY-20yLFpGis0yMb6ISJGqnE9rW19-I7vphkvbvvUMYf0TIUH28KV64PZWn4oprm7UPRL_vtEA2Pe5ANOFAEC5aIBY08RBgVTNT3N4Gppb3edvGq3-0uHQYCCCZK7WTgFl_j_i9798bEuQwAyWMEA7WCFVH5vacf6y5Ic8emoW91S8W209IvzXcUlWKsslkER1_qKCt0h',
            'avatar' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD9y59Jh91vTJJ3DgOPYsXnNXTWJ43VMrIeq4TAPnXMKLTeux2PbMsqcAHPm9nJGyPoEqmq83FAByfLmSepCkzuS8rYF-L-UMQm8mOvuEdYR-0cz9x26g0oYT2HGGsOrtge-tQK5XTNFVtaVkP0c_pudOOSHyFDFexVmcOVRVncsESXEY4pTJFGawCmT2mZNiX3u3v1SbkPEhL9Cj_fmFvnUEl__bByPbHFEhSTXQS5oS_8eAq2EWHJUchUKscQBPQNBo19G_2f2ONm'
        ],
        [
            'category' => 'Food',
            'user' => 'Plant Life',
            'title' => 'Meat-Free Mondays',
            'description' => 'Starting small makes a big difference! Try going meat-free just one day a week. It reduces greenhouse gas emissions, saves water, and is great for your health. Need recipe ideas? Check out our plant-based collection!',
            'likes' => '1.3k',
            'comments' => 92,
            'image' => null,
            'avatar' => 'https://i.pravatar.cc/150?u=plantlife'
        ],
    ];

    return view('saved', compact('tips'));
})->middleware('auth')->name('saved');

// PROFILE (Protected Route - Solo para usuarios autenticados)
Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

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
| IDIOMA
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'de'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
});

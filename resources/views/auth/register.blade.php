<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>GreenBoard - Join the Green Revolution</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec5b",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102216",
                    },
                    fontFamily: { "display": ["Plus Jakarta Sans"] },
                    borderRadius: { "xl": "0.75rem" },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen p-0 m-0 overflow-x-hidden">

<div class="flex min-h-screen w-full flex-col lg:flex-row">
    
   <div id="hero-section" class="flex lg:w-1/2 relative overflow-hidden bg-primary/10 h-screen lg:min-h-screen cursor-pointer lg:cursor-default group" onclick="showForm()">
        <div class="absolute inset-0 bg-cover bg-center z-0 transition-transform duration-700 group-hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80');"></div>
        
        <div class="absolute inset-0 bg-gradient-to-t from-background-dark/90 via-transparent to-transparent z-10"></div>
        
        <div class="relative z-20 flex flex-col justify-end p-10 lg:p-16 w-full h-full">
            <div class="flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-primary text-4xl">eco</span>
                <span class="text-white text-3xl font-extrabold tracking-tight">GreenBoard</span>
            </div>
            <h1 class="text-white text-4xl lg:text-5xl font-black leading-tight mb-4">Join the Green Revolution</h1>
            <p class="text-slate-200 text-lg max-w-md mb-6 lg:mb-0">Connect with eco-conscious individuals and take measurable action towards a sustainable future.</p>
            
            <div class="lg:hidden flex items-center text-primary font-bold animate-bounce mt-4">
                <span class="material-symbols-outlined mr-2">touch_app</span>
                Tap to start
            </div>
        </div>
    </div>

    <div id="form-section" class="hidden lg:flex flex-1 flex-col justify-center items-center px-6 py-12 lg:px-20 bg-background-light dark:bg-background-dark fade-in">
        <div class="w-full max-w-[440px]">
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">Create an account</h2>
                <p class="text-slate-600 dark:text-slate-400">Start your environmental journey today.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Full Name</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">person</span>
                        <input name="name" value="{{ old('name') }}" required class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-900 border @error('name') border-red-500 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="John Doe" type="text"/>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email Address</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">mail</span>
                        <input name="email" value="{{ old('email') }}" required class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-900 border @error('email') border-red-500 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="john@example.com" type="email"/>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
                        <input name="password" required class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-900 border @error('password') border-red-500 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="Min. 8 characters" type="password"/>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Confirm Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">lock</span>
                        <input name="password_confirmation" required class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="Repeat password" type="password"/>
                    </div>
                </div>

                <button class="w-full bg-primary text-slate-900 font-extrabold text-base py-4 rounded-xl shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-[0.95]" type="submit">
                    Sign Up Now
                </button>
            </form>

            <p class="text-center mt-8 text-slate-600 dark:text-slate-400">
                Already have an account? 
                <a class="text-primary font-bold hover:underline" href="{{ route('login') }}">Log in</a>
            </p>
        </div>
    </div>
</div>

<script>
    function showForm() {
        
        if (window.innerWidth < 1024) {
            const hero = document.getElementById('hero-section');
            const form = document.getElementById('form-section');
            
            hero.classList.add('hidden'); 
            form.classList.remove('hidden'); 
            form.classList.add('flex');
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
</script>

</body>
</html>
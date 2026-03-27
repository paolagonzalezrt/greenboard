@extends('layouts.app')

@section('title', 'GreenBoard - About Us')

@section('content')
<!-- Hero Section -->
<header class="relative overflow-hidden pt-16 pb-24 lg:pt-32 lg:pb-40">
<div class="max-w-7xl mx-auto px-6 relative z-10">
<div class="grid lg:grid-cols-12 gap-12 items-center">
<div class="lg:col-span-7">
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold uppercase tracking-wider mb-6">
<span class="relative flex h-2 w-2">
<span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
<span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
</span>
                    Reimagining Campus Life
                </div>
<h1 class="text-6xl md:text-8xl font-black leading-[0.9] tracking-tighter mb-8">
                    The <span class="text-primary italic">Heartbeat</span> of Campus Ecology.
                </h1>
<p class="text-xl text-slate-600 dark:text-slate-400 max-w-xl leading-relaxed">
                    GreenBoard isn't just a platform; it's a living ecosystem where university students unite to plant the seeds of a sustainable future.
                </p>
</div>
<div class="lg:col-span-5 relative">
<div class="organic-shape bg-primary/20 absolute -inset-4 blur-3xl"></div>
<div class="relative rounded-[2rem] overflow-hidden border-8 border-white dark:border-slate-800 shadow-2xl rotate-3">
<img alt="Students collaborating on a green project" class="w-full h-[500px] object-cover" data-alt="A lush, green university campus with modern sustainable architecture and students walking" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCG8lNDdy9WVPlyt3GB9wsw9iZi7vyueK4ipm1_GslNkmfHmXXc57FfXboRoRCkoIAVXZ9uUPhEydlItjBQJ8HL6Z1CVqpxbeIrXxo_no2K3-eDnrNoR0oku2M_qg1P7f187svu7tPEzywGb6jVzTQB5Y2HXHFnjV-bY71_MuoKIuUAP3bfDHNh6RViaLpBB-RncOnVBHxVESq-GzdMzYcUNrOUcpLJqQjXc0nvirHs5aH2pDf0lY7zKJbDBTqeQr-WhVGzU4C1SsB4"/>
</div>
<div class="absolute -bottom-6 -left-6 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl max-w-[200px] -rotate-6">
<span class="material-symbols-outlined text-primary text-4xl mb-2">groups</span>
<p class="text-xs font-bold uppercase text-slate-400">Community Driven</p>
<p class="text-sm font-semibold text-slate-900 dark:text-white">Built by students, for the planet.</p>
</div>
</div>
</div>
</div>
</header>
<!-- Mission Section -->
<section class="py-24 bg-background-light dark:bg-background-dark relative overflow-hidden">
<div class="max-w-7xl mx-auto px-6 relative z-10">
<div class="text-center max-w-3xl mx-auto mb-20">
<h2 class="text-sm font-bold text-primary uppercase tracking-[0.2em] mb-4">Our Mission</h2>
<p class="text-4xl md:text-5xl font-extrabold leading-tight text-slate-900 dark:text-white">
                Strengthening campus sustainability through <span class="text-primary italic">radical collaboration</span> and digital empowerment.
            </p>
</div>
<div class="grid md:grid-cols-2 gap-8 lg:gap-12 items-center">
<!-- Card 1 -->
<div class="group relative p-10 rounded-[2.5rem] bg-white dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
<div class="mb-8 inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-background-dark transition-colors duration-300">
<span class="material-symbols-outlined text-3xl font-light">hub</span>
</div>
<h3 class="text-2xl font-bold mb-4 text-slate-900 dark:text-white">Uniting Fragments</h3>
<p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                    We connect isolated eco-clubs and passionate individuals into one powerful, unified university network, turning local ripples into a global wave.
                </p>
<div class="mt-8 h-1 w-12 bg-primary rounded-full group-hover:w-24 transition-all duration-500"></div>
</div>
<!-- Card 2 -->
<div class="group relative p-10 rounded-[2.5rem] bg-white dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
<div class="mb-8 inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-background-dark transition-colors duration-300">
<span class="material-symbols-outlined text-3xl font-light">energy_savings_leaf</span>
</div>
<h3 class="text-2xl font-bold mb-4 text-slate-900 dark:text-white">Action-Oriented</h3>
<p class="text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                    From waste reduction to clean energy projects, we provide the digital infrastructure to track, manage, and scale high-impact green initiatives.
                </p>
<div class="mt-8 h-1 w-12 bg-primary rounded-full group-hover:w-24 transition-all duration-500"></div>
</div>
</div>
</div>
</section>
<!-- Call to Action -->
<section class="px-6 py-20 lg:py-32 bg-background-light dark:bg-background-dark">
<div class="max-w-5xl mx-auto rounded-[3rem] p-12 lg:p-20 relative overflow-hidden flex flex-col items-center text-center border border-primary/20 bg-primary/5">
<div class="relative z-10">
<h2 class="text-4xl md:text-6xl font-black mb-6 leading-tight text-slate-900 dark:text-white">
                Ready to leave your <br/><span class="text-primary/70">eco-legacy?</span>
</h2>
<p class="text-lg font-medium mb-10 max-w-xl mx-auto leading-relaxed text-slate-600 dark:text-slate-400">
                Join thousands of students who are turning climate anxiety into climate action. Your campus needs your voice.
            </p>
<div class="flex flex-col sm:flex-row gap-4 justify-center">
<button class="bg-primary text-background-dark px-10 py-4 rounded-full font-bold text-lg hover:bg-primary/90 transition-all shadow-sm">
                    Create Your Profile
                </button>
<button class="bg-transparent text-slate-900 dark:text-white border border-slate-300 dark:border-slate-600 px-10 py-4 rounded-full font-bold text-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    Explore Initiatives
                </button>
</div>
</div>
</div>
</section>
@endsection

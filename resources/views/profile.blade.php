@extends('layouts.app')

@section('content')
    <!-- Unified Profile Header Section -->
    <div class="mb-8 sm:mb-10 lg:mb-12 w-full">
        <div class="flex flex-col lg:flex-row justify-between gap-6 sm:gap-8">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 lg:gap-8">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    <div class="size-20 sm:size-24 md:size-32 rounded-full border-4 border-white dark:border-slate-800 bg-slate-200 shadow-lg overflow-hidden mx-auto sm:mx-0">
                        <img alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=400&background=13ec5b&color=102216&bold=true"/>
                    </div>
                </div>

                <!-- Info Column -->
                <div class="flex-1 flex flex-col gap-3 sm:gap-4 text-center sm:text-left">
                    <div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</h1>
                    </div>
                    <div class="max-w-2xl">
                        <p class="text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-400">
                            Urban gardener and zero-waste enthusiast. I've been on a mission to reduce my environmental footprint since 2020. Lover of composting, native plants, and DIY upcycling. Join me in making the world a bit greener, one tip at a time! 🌱
                        </p>
                    </div>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-x-4 sm:gap-x-8 gap-y-2">
                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">calendar_today</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">Joined {{ Auth::user()->created_at->format('F Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-primary text-lg sm:text-xl">location_on</span>
                            <span class="text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200">Portland, OR</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 sm:gap-3 flex-shrink-0 items-start justify-center sm:justify-start">
                <button class="flex items-center gap-2 rounded-full bg-primary px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-background-dark shadow-md transition-all hover:brightness-105">
                    <span class="material-symbols-outlined text-base sm:text-lg">edit</span>
                    <span class="hidden sm:inline">Edit Profile</span>
                    <span class="sm:hidden">Edit</span>
                </button>
                <button class="flex items-center gap-2 rounded-full bg-white dark:bg-slate-800 px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-bold shadow-md transition-all border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700">
                    <span class="material-symbols-outlined text-base sm:text-lg">share</span>
                    <span class="hidden sm:inline">Share</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 sm:mb-8 w-full">
        <div class="flex border-b border-slate-200 dark:border-slate-800 overflow-x-auto no-scrollbar">
            <button class="border-b-2 border-primary px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold text-primary whitespace-nowrap">My Tips</button>
            <button class="px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors whitespace-nowrap">Saved</button>
            <button class="px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors whitespace-nowrap">Challenges</button>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="w-full">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 lg:gap-6">
            @php
                $userPosts = [
                    [
                        'category' => 'Consumption',
                        'user' => Auth::user()->name,
                        'title' => '10 Plastic-Free Bathroom Swaps',
                        'description' => 'Starting my journey with zero-waste toiletries was easier than I thought. Here are my top 10 sustainable swaps.',
                        'likes' => 242,
                        'comments' => 18,
                        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA3PH4lRZXIa09V4zalEmbC0xQHUOCPPBjrcOjBTXI1lRUFHrucSVNW4HHB-3NXgAQkNyCvZtU1AKvpu6C5cY7hkrm9GS3SWcixOIu43kD3ivqlWLxCOH8btE3H0uvb7eT2-8a4Xn1F0cZ-CxAk2O-RXw3BvkUTNPZ567Bg8Ag0wO46AKw3Zctfu4UsL9D0LnvxsjCuYFhRa2MndbugcFXliIVaVm1gSEBLgunRb5ff2tHjlT96pWrf5uaqQ7UbeZWxbxU4GNXJhGHH',
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&background=13ec5b&color=102216&bold=true'
                    ],
                    [
                        'category' => 'Food',
                        'user' => Auth::user()->name,
                        'title' => 'My Summer Garden Harvest Guide',
                        'description' => 'The best part about growing your own food is the incredible flavor. Here\'s what I learned this season.',
                        'likes' => 512,
                        'comments' => 42,
                        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDawxaWGxnrkVIOSjNg8RcDSHe68l5eQMNsvUtaMFYJoPRFntMxA0ivD18-Mc7bkjfCncKA6E1_eJYseJX_4NVS4XKy0eI7vDf9rRT-zfoKMLskEw-8j8Hn1-l4gr9S7sUdyucqsqu9nkSyhDQR59Fa1wLLiibP32_DYetoZ3O9OWQOcGJ39PToHv4nkQ7DnX6TYlzS1V2Xd-sfyEi3JPw127j7hf4Ijuhircd66Qu3pZGMrNuXJjkSvVjzPoC7JaqAUGymKIiBfJsh',
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&background=13ec5b&color=102216&bold=true'
                    ],
                    [
                        'category' => 'Home',
                        'user' => Auth::user()->name,
                        'title' => 'Composting for Beginners: Top 5 Tips',
                        'description' => 'Don\'t let your food waste go to landfill. Here is how I started composting at home successfully.',
                        'likes' => 318,
                        'comments' => 29,
                        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAGcp0SYxsntOqpci6hT6DfSeCMNktXSqP_UYp-y4E0ekDZcuvYtAu6UHOMZWQEJ9ym5T1gV5Usd27nB9OL2XmrW69Wb1wqv2ADSpT21JtfKbJ3o6rjo_Gy0WiU84dmrPcjUT-xPPs2tbW8ogoWadd2PtuaIMkeSfS-5368uZLwaF38XLwlcfejdg0Guz6ozrjtbkDKeC6RW39Hk4G1IojmpORydpyvHfmW-kJ800ITJsc4MFfsJJHkhvMP7vSmmvx204FnbfPYF1pB',
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&background=13ec5b&color=102216&bold=true'
                    ],
                    [
                        'category' => 'Consumption',
                        'user' => Auth::user()->name,
                        'title' => 'Why I switched to Bulk Shopping',
                        'description' => 'Cutting out single-use packaging has saved me money and reduced my bin waste significantly.',
                        'likes' => 156,
                        'comments' => 12,
                        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDKgqCJOBnoaHL1jxQgYjfTVuRylJfPomdmLI-2ZcfbA5OZRdxJqKxt6yM_xB02_tkvtJMOUuhUa7gNBKfbKycg-OAoBcYpriRN-8O0z0e7MxO3esyFleC0u5kS-uS_NR9jZ08xvHZTea28voyy1rdqBXDDCSDgcR2DdT91JI_Nk4FZsCEYk962VScHgweP6-Q3wbj_6CZP1kbLj5NWgDuzpuwekJVvDLpBmQ-0nJ3OIoR6YHGTJGtLXwfBOsi-9rDLy0Jv4SmZ7lC5',
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&background=13ec5b&color=102216&bold=true'
                    ],
                    [
                        'category' => 'Energy',
                        'user' => Auth::user()->name,
                        'title' => 'Solar panels: My first year review',
                        'description' => 'After installing solar panels, here\'s what I learned about costs, savings, and environmental impact.',
                        'likes' => 445,
                        'comments' => 37,
                        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCpNIdtFNei9u_BWssoyjMPL403G9kJr8c9r9wn7263TzLjZoJFitKjNXKIzYz_GQh-ILrnY-20yLFpGis0yMb6ISJGqnE9rW19-I7vphkvbvvUMYf0TIUH28KV64PZWn4oprm7UPRL_vtEA2Pe5ANOFAEC5aIBY08RBgVTNT3N4Gppb3edvGq3-0uHQYCCCZK7WTgFl_j_i9798bEuQwAyWMEA7WCFVH5vacf6y5Ic8emoW91S8W209IvzXcUlWKsslkER1_qKCt0h',
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&background=13ec5b&color=102216&bold=true'
                    ],
                    [
                        'category' => 'Transport',
                        'user' => Auth::user()->name,
                        'title' => 'Bike commuting changed my life',
                        'description' => 'How switching from car to bicycle transformed my health, wallet, and perspective on urban living.',
                        'likes' => 789,
                        'comments' => 56,
                        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC6zaMnFCg3KaT5gDXiI7xHTnNHJyyH9_w-VN3EDHyigjm_J5vM3AR4LqfIJdBFsG8v-P8LzpzSlcz23Ydm1LSTA3ktrkUb5oOgnJFrQREebmB9p8RTF7zZ1ukVpJiEVJpar8bIz9rZXYh_YvumJvIgp70JJCYEPOXO3siMd95yPm37p2bRga1CLWc0scNjC7XrjxA47BrwipIjG-Ts9bblBGCvG2lZP0DaN7dsMQv9NUaJoGEptd2dc6PRb4LuivXdNoyMgG9fTra5',
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&size=100&background=13ec5b&color=102216&bold=true'
                    ],
                ];
            @endphp

            @foreach($userPosts as $post)
                <x-tip-card 
                    :category="$post['category']"
                    :user="$post['user']"
                    :title="$post['title']"
                    :description="$post['description']"
                    :likes="$post['likes']"
                    :comments="$post['comments']"
                    :image="$post['image']"
                    :avatar="$post['avatar']"
                />
            @endforeach
        </div>

        <!-- Load More Button -->
        <div class="mt-8 sm:mt-10 lg:mt-12 flex justify-center">
            <button class="border-2 border-primary/30 px-6 sm:px-8 py-2 sm:py-2.5 text-xs sm:text-sm font-bold text-primary hover:bg-primary/10 transition-colors rounded-full">
                Load More
            </button>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection

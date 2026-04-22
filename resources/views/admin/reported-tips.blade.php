@extends('layouts.app')

@section('title', 'Panel de Administración - Reportes')

@section('content')
    <div class="w-full max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ __('admin.admin_title') }}</h1>
            </div>
            <p class="text-slate-600 dark:text-slate-400 text-lg">{{ __('admin.admin_subtitle') }}</p>
        </div>

        {{-- Success message removed to avoid duplication with the global floating notifier --}}

        <!-- Error Message -->
        @if(session('error'))
            <x-alert-notification 
                message="{{ session('error') }}"
                type="error"
                icon="error"
                class="mb-6 animate-fade-in"
            />
        @endif

        <!-- Tabs Navigation -->
        <div class="mb-6 sm:mb-8 w-full">
            <div class="flex border-b border-slate-200 dark:border-slate-800 overflow-x-auto no-scrollbar">
                <a href="javascript:void(0)" onclick="switchTab('tips')" id="tabs-tips" class="border-b-2 border-primary text-primary px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap active-tab">
                    {{ __('admin.reported_tips_tab') }}
                </a>
                <a href="javascript:void(0)" onclick="switchTab('comments')" id="tabs-comments" class="border-b-2 border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 px-4 sm:px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-bold transition-colors whitespace-nowrap">
                    {{ __('admin.reported_comments_tab') }}
                </a>
            </div>
        </div>

        <!-- TIPS TAB -->
        <div id="tab-content-tips" class="tab-content">
            <!-- Stats Cards -->


            <!-- Reported Tips List -->
            @if($reportedTips->isEmpty())
                <div class="bg-white dark:bg-slate-800 rounded-xl p-12 text-center border-2 border-slate-200 dark:border-slate-700">
                    <span class="material-symbols-outlined text-6xl text-slate-400 mb-4 block">check_circle</span>
                    <h3 class="text-xl font-bold text-slate-600 dark:text-slate-400 mb-2">{{ __('admin.all_clean') }}</h3>
                    <p class="text-slate-500 dark:text-slate-500">{{ __('admin.no_reported_tips') }}</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($reportedTips as $tip)
                        <div class="bg-white dark:bg-slate-800 rounded-xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden hover:border-primary/50 transition-colors">
                            <!-- Tip Header -->
                            <div class="p-6 border-b-2 border-slate-200 dark:border-slate-700">
                                <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold mb-2">{{ $tip['title'] }}</h3>
                                        <p class="text-slate-600 dark:text-slate-400 mb-3 line-clamp-2">{{ $tip['description'] }}</p>
                                        <div class="flex items-center gap-2 text-sm text-slate-500">
                                            <span class="material-symbols-outlined text-sm">person</span>
                                            <span>Autor: <strong>{{ $tip['author'] }}</strong> ({{ $tip['author_email'] }})</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                                            <span>Publicado: {{ $tip['created_at'] }}</span>
                                        </div>
                                    </div>
                                    
                                    @if($tip['image'])
                                        <div class="w-full sm:w-32 h-32 rounded-lg overflow-hidden border-2 border-slate-200 dark:border-slate-700">
                                            <img src="{{ asset('storage/' . $tip['image']) }}" alt="{{ $tip['title'] }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Reports List -->
                            <div class="p-6 bg-slate-50 dark:bg-slate-900/50">
                                <h4 class="text-lg font-bold mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-500">report</span>
                                    {{ __('admin.reports_received') }}
                                </h4>
                                <div class="space-y-3">
                                    @foreach($tip['reports'] as $report)
                                        <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border-2 border-slate-200 dark:border-slate-700">
                                            <div class="flex flex-col sm:flex-row justify-between items-start gap-3 mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-bold rounded-full">
                                                            {{ __('admin.reason_' . ($report['reason'] === 'misleading' ? 'misinformation' : $report['reason'])) }}
                                                        </span>

                                                    </div>
                                                    <p class="text-slate-700 dark:text-slate-300 mb-2">{{ $report['description'] ?? __('admin.no_description') }}</p>
                                                    <div class="text-xs text-slate-500">
                                                        <span class="font-semibold">{{ __('admin.reported_by_label') }}</span> {{ $report['reporter'] }} ({{ $report['reporter_email'] }})
                                                        <span class="mx-2">•</span>
                                                        <span>{{ $report['created_at'] }}</span>
                                                    </div>
                                                </div>
                                                

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="p-6 border-t-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                    <a href="{{ route('tips.show', $tip['id']) }}" target="_blank" class="w-full sm:w-auto px-5 py-2.5 bg-blue-500 text-white text-sm font-bold rounded-full hover:bg-blue-600 transition-colors text-center">
                                        {{ __('admin.view_full_post') }}
                                    </a>
                                    <div class="flex flex-wrap sm:flex-nowrap gap-3 w-full sm:w-auto">
                                        <form action="{{ route('admin.tips.dismiss-all', $tip['id']) }}" method="POST" class="w-full sm:w-auto" onsubmit="confirmAction(event, this, 'confirm-dismiss-modal')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-5 py-2.5 bg-green-500 text-white text-sm font-bold rounded-full hover:bg-green-600 transition-colors">
                                                {{ __('admin.discard_report') }}
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('admin.tips.delete', $tip['id']) }}" method="POST" class="w-full sm:w-auto" onsubmit="confirmAction(event, this, 'confirm-delete-tip-modal')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-5 py-2.5 bg-red-500 text-white text-sm font-bold rounded-full hover:bg-red-600 transition-colors">
                                                {{ __('admin.delete_post') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- COMMENTS TAB -->
        <div id="tab-content-comments" class="tab-content hidden">
            <!-- Stats Cards -->


            <!-- Reported Comments List -->
            @if($reportedComments->isEmpty())
                <div class="bg-white dark:bg-slate-800 rounded-xl p-12 text-center border-2 border-slate-200 dark:border-slate-700">
                    <span class="material-symbols-outlined text-6xl text-slate-400 mb-4 block">check_circle</span>
                    <h3 class="text-xl font-bold text-slate-600 dark:text-slate-400 mb-2">{{ __('admin.all_clean') }}</h3>
                    <p class="text-slate-500 dark:text-slate-500">{{ __('admin.no_reported_comments') }}</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($reportedComments as $comment)
                        <div class="bg-white dark:bg-slate-800 rounded-xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden hover:border-primary/50 transition-colors">
                            <!-- Comment Header -->
                            <div class="p-6 border-b-2 border-slate-200 dark:border-slate-700">
                                <div class="flex flex-col gap-3">
                                    <div>
                                        <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-2">Comentario en post:</h3>
                                        <a href="{{ route('tips.show', $comment['tip_id']) }}" target="_blank" class="text-lg font-bold text-primary hover:underline">
                                            {{ $comment['tip_title'] }}
                                        </a>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-slate-900 p-4 rounded-lg">
                                        <p class="text-slate-700 dark:text-slate-300">{{ $comment['content'] }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-slate-500">
                                        <span class="material-symbols-outlined text-sm">person</span>
                                        <span>Autor: <strong>{{ $comment['author'] }}</strong> ({{ $comment['author_email'] }})</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-slate-500">
                                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                                        <span>Publicado: {{ $comment['created_at'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Reports List -->
                            <div class="p-6 bg-slate-50 dark:bg-slate-900/50">
                                <h4 class="text-lg font-bold mb-4 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-red-500">report</span>
                                    {{ __('admin.reports_received') }}
                                </h4>
                                <div class="space-y-3">
                                    @foreach($comment['reports'] as $report)
                                        <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border-2 border-slate-200 dark:border-slate-700">
                                            <div class="flex flex-col sm:flex-row justify-between items-start gap-3 mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-bold rounded-full">
                                                            {{ __('admin.reason_' . ($report['reason'] === 'misleading' ? 'misinformation' : $report['reason'])) }}
                                                        </span>

                                                    </div>
                                                    <p class="text-slate-700 dark:text-slate-300 mb-2">{{ $report['description'] ?? __('admin.no_description') }}</p>
                                                    <div class="text-xs text-slate-500">
                                                        <span class="font-semibold">{{ __('admin.reported_by_label') }}</span> {{ $report['reporter'] }} ({{ $report['reporter_email'] }})
                                                        <span class="mx-2">•</span>
                                                        <span>{{ $report['created_at'] }}</span>
                                                    </div>
                                                </div>
                                                

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="p-6 border-t-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                    <a href="{{ route('tips.show', $comment['tip_id']) }}#comment-{{ $comment['id'] }}" target="_blank" class="w-full sm:w-auto px-5 py-2.5 bg-blue-500 text-white text-sm font-bold rounded-full hover:bg-blue-600 transition-colors text-center">
                                        {{ __('admin.view_comment') }}
                                    </a>
                                    <div class="flex flex-wrap sm:flex-nowrap gap-3 w-full sm:w-auto">
                                        <form action="{{ route('admin.comments.dismiss-all', $comment['id']) }}" method="POST" class="w-full sm:w-auto" onsubmit="confirmAction(event, this, 'confirm-dismiss-modal')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-5 py-2.5 bg-green-500 text-white text-sm font-bold rounded-full hover:bg-green-600 transition-colors">
                                                {{ __('admin.discard_report') }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.comments.delete', $comment['id']) }}" method="POST" class="w-full sm:w-auto" onsubmit="confirmAction(event, this, 'confirm-delete-comment-modal')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-5 py-2.5 bg-red-500 text-white text-sm font-bold rounded-full hover:bg-red-600 transition-colors">
                                                {{ __('admin.delete_comment') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-in-out;
        }
        
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
    </style>

    <script>
        function switchTab(tabName) {
            // Classes constants
            const activeClasses = ['border-primary', 'text-primary'];
            const inactiveClasses = ['border-transparent', 'text-slate-500', 'hover:text-slate-700', 'dark:hover:text-slate-300'];

            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Update all tabs to inactive state
            document.querySelectorAll('[id^="tabs-"]').forEach(tab => {
                tab.classList.remove('active-tab', ...activeClasses);
                tab.classList.add(...inactiveClasses);
            });
            
            // Show selected tab content
            const content = document.getElementById(`tab-content-${tabName}`);
            if (content) content.classList.remove('hidden');
            
            // Set selected tab to active state
            const activeTab = document.getElementById(`tabs-${tabName}`);
            if (activeTab) {
                activeTab.classList.add('active-tab', ...activeClasses);
                activeTab.classList.remove(...inactiveClasses);
            }
        }
        
        // Set initial active tab
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('tips');
        });
    </script>
    <!-- Modales de confirmación -->
    <x-confirm-modal 
        id="confirm-dismiss-modal" 
        title="{{ __('admin.discard_report') }}"
        message="{{ __('admin.confirm_dismiss_all') }}"
        confirmText="{{ __('admin.discard') }}"
        cancelText="{{ __('admin.cancel') }}"
        onConfirm="performConfirmedAction"
        isDangerous="false"
    />

    <x-confirm-modal 
        id="confirm-delete-tip-modal" 
        title="{{ __('admin.delete_post') }}"
        message="{{ __('admin.confirm_delete_post') }}"
        confirmText="{{ __('admin.delete_tip') ?? 'Eliminar' }}"
        cancelText="{{ __('admin.cancel') }}"
        onConfirm="performConfirmedAction"
        isDangerous="true"
    />

    <x-confirm-modal 
        id="confirm-delete-comment-modal" 
        title="{{ __('admin.delete_comment') }}"
        message="{{ __('admin.confirm_delete_comment') }}"
        confirmText="{{ __('admin.delete_comment') }}"
        cancelText="{{ __('admin.cancel') }}"
        onConfirm="performConfirmedAction"
        isDangerous="true"
    />

    <script>
        let formToSubmit = null;

        function confirmAction(event, form, modalId) {
            event.preventDefault();
            formToSubmit = form;
            showConfirmModal(modalId);
        }

        window.performConfirmedAction = function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        };
    </script>
@endsection

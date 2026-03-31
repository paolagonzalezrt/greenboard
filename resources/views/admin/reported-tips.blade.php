@extends('layouts.app')

@section('title', 'Panel de Administración - Tips Reportados')

@section('content')
    <div class="w-full max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-red-500 text-4xl">report</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Panel de Administración</h1>
            </div>
            <p class="text-slate-600 dark:text-slate-400 text-lg">Gestión de tips reportados por la comunidad</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-500 text-green-700 dark:text-green-400 rounded-xl flex items-center gap-3 animate-fade-in">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-500 text-red-700 dark:text-red-400 rounded-xl flex items-center gap-3 animate-fade-in">
                <span class="material-symbols-outlined">error</span>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border-2 border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold mb-1">Total Tips Reportados</p>
                        <p class="text-3xl font-extrabold text-primary">{{ $reportedTips->count() }}</p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-red-500 opacity-20">report</span>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border-2 border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold mb-1">Reportes Pendientes</p>
                        <p class="text-3xl font-extrabold text-orange-500">
                            {{ $reportedTips->sum(function($tip) { 
                                return $tip['reports']->where('status', 'pending')->count(); 
                            }) }}
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-orange-500 opacity-20">pending</span>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border-2 border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold mb-1">Total Reportes</p>
                        <p class="text-3xl font-extrabold text-blue-500">
                            {{ $reportedTips->sum('reports_count') }}
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-blue-500 opacity-20">analytics</span>
                </div>
            </div>
        </div>

        <!-- Reported Tips List -->
        @if($reportedTips->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-xl p-12 text-center border-2 border-slate-200 dark:border-slate-700">
                <span class="material-symbols-outlined text-6xl text-slate-400 mb-4 block">check_circle</span>
                <h3 class="text-xl font-bold text-slate-600 dark:text-slate-400 mb-2">¡Todo limpio!</h3>
                <p class="text-slate-500 dark:text-slate-500">No hay tips reportados en este momento.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($reportedTips as $tip)
                    <div class="bg-white dark:bg-slate-800 rounded-xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden hover:border-primary/50 transition-colors">
                        <!-- Tip Header -->
                        <div class="p-6 border-b-2 border-slate-200 dark:border-slate-700">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 bg-{{ $tip['category'] === 'Energy' ? 'amber' : ($tip['category'] === 'Home' ? 'blue' : ($tip['category'] === 'Transport' ? 'emerald' : ($tip['category'] === 'Food' ? 'orange' : 'purple'))) }}-100 text-{{ $tip['category'] === 'Energy' ? 'amber' : ($tip['category'] === 'Home' ? 'blue' : ($tip['category'] === 'Transport' ? 'emerald' : ($tip['category'] === 'Food' ? 'orange' : 'purple'))) }}-700 text-xs font-bold rounded-full">
                                            {{ $tip['category'] }}
                                        </span>
                                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-bold rounded-full flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">report</span>
                                            {{ $tip['reports_count'] }} {{ $tip['reports_count'] === 1 ? 'Reporte' : 'Reportes' }}
                                        </span>
                                    </div>
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
                                Reportes Recibidos
                            </h4>
                            <div class="space-y-3">
                                @foreach($tip['reports'] as $report)
                                    <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border-2 border-slate-200 dark:border-slate-700">
                                        <div class="flex flex-col sm:flex-row justify-between items-start gap-3 mb-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400 text-xs font-bold rounded-full">
                                                        {{ $report['reason'] }}
                                                    </span>
                                                    <span class="px-2 py-1 text-xs font-bold rounded-full
                                                        {{ $report['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                        {{ $report['status'] === 'reviewed' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $report['status'] === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                                                        {{ $report['status'] === 'dismissed' ? 'bg-gray-100 text-gray-700' : '' }}">
                                                        {{ ucfirst($report['status']) }}
                                                    </span>
                                                </div>
                                                <p class="text-slate-700 dark:text-slate-300 mb-2">{{ $report['description'] ?? 'Sin descripción adicional' }}</p>
                                                <div class="text-xs text-slate-500">
                                                    <span class="font-semibold">Reportado por:</span> {{ $report['reporter'] }} ({{ $report['reporter_email'] }})
                                                    <span class="mx-2">•</span>
                                                    <span>{{ $report['created_at'] }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Status Update Form -->
                                            <form action="{{ route('admin.reports.update-status', $report['id']) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="text-xs px-2 py-1 rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
                                                    <option value="pending" {{ $report['status'] === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                                    <option value="reviewed" {{ $report['status'] === 'reviewed' ? 'selected' : '' }}>Revisado</option>
                                                    <option value="resolved" {{ $report['status'] === 'resolved' ? 'selected' : '' }}>Resuelto</option>
                                                    <option value="dismissed" {{ $report['status'] === 'dismissed' ? 'selected' : '' }}>Descartado</option>
                                                </select>
                                                <button type="submit" class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-lg hover:bg-blue-600 transition-colors">
                                                    Actualizar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="p-6 border-t-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <a href="{{ route('tips.show', $tip['id']) }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-600 transition-colors flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">open_in_new</span>
                                    Ver Tip Completo
                                </a>
                                
                                <form action="{{ route('admin.tips.delete', $tip['id']) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este tip? Esta acción no se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-colors flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined">delete</span>
                                        Eliminar Tip
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
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
    </style>
@endsection

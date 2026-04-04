@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-xl">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-red-500 text-xl flex-shrink-0">error</span>
            <div class="flex-1">
                @if ($errors->count() === 1)
                    <p class="text-sm font-medium text-red-700 dark:text-red-400">
                        {{ $errors->first() }}
                    </p>
                @else
                    <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">
                        {{ __('components.errors_found') }}
                    </p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red-600 dark:text-red-300">{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endif

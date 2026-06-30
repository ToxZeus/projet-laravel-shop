@if(session('status'))
    <div x-data="{ show: true }" x-show="show" x-transition class="alert-success mb-4">
        <x-icon name="check-circle" class="w-5 h-5 shrink-0" />
        <p class="flex-1">{{ session('status') }}</p>
        <button @click="show = false" class="shrink-0 text-emerald-600 hover:text-emerald-800 dark:text-emerald-300">
            <x-icon name="x-circle" class="w-4 h-4" />
        </button>
    </div>
@endif
@if(session('info'))
    <div x-data="{ show: true }" x-show="show" x-transition class="alert-info mb-4">
        <x-icon name="clock" class="w-5 h-5 shrink-0" />
        <p class="flex-1">{{ session('info') }}</p>
        <button @click="show = false" class="shrink-0 text-amber-600 hover:text-amber-800 dark:text-amber-300">
            <x-icon name="x-circle" class="w-4 h-4" />
        </button>
    </div>
@endif
@if($errors->any())
    <div x-data="{ show: true }" x-show="show" x-transition class="alert-error mb-4">
        <x-icon name="x-circle" class="w-5 h-5 shrink-0" />
        <ul class="flex-1 space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button @click="show = false" class="shrink-0 text-red-600 hover:text-red-800 dark:text-red-300">
            <x-icon name="x-circle" class="w-4 h-4" />
        </button>
    </div>
@endif

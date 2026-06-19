@if(session('status'))
    <div class="mb-4 text-green-700">{{ session('status') }}</div>
@endif
@if(session('info'))
    <div class="mb-4 text-yellow-700">{{ session('info') }}</div>
@endif

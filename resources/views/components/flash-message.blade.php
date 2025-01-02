@if (session()->has('status') && session()->has('message'))
    <div class="alert alert-{{ session('status') }}" role="alert">
        {{ session('message') }}
    </div>
@endif
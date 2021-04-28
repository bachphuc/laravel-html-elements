@if(session()->has('message') || session()->has('error') || session()->has('warning') || (isset($message) && !empty($message)))
<div class="mm">
@endif

    @if(session()->has('message'))
    <div class="alert alert-success" role="alert">
        ✅ {{session('message')}}
    </div>
    @endif
    @if(isset($message) && !empty($message))
    <div class="alert alert-success" role="alert">
        ✅ {{$message}}
    </div>
    @endif
    @if(session()->has('error'))
    <div class="alert alert-danger" role="alert">
        ❌ {{session('error')}}
    </div>
    @endif
    @if(session()->has('warning'))
    <div class="alert alert-warning" role="alert">
        ⚠️ {{session('warning')}}
    </div>
    @endif

@if(session()->has('message') || session()->has('error') || session()->has('warning') || (isset($message) && !empty($message)))
</div>
@endif
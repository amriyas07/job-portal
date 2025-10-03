@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-sm auth-alert-success']) }}>
        {{ $status }}
    </div>
@endif

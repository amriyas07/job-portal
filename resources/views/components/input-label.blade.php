@props(['value'])

<label {{ $attributes->merge(['class' => 'block auth-label']) }}>
    {{ $value ?? $slot }}
</label>

@props(['value','type'])

<button {{ $attributes->merge(['class' => 'button', 'type' => $type ?? 'button']) }}>
    {{ $value ?? $slot }}
</button>

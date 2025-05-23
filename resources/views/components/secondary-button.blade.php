@props(['value','type'])

<button {{ $attributes->merge(['class' => 'button button__secondary', 'type' => $type ?? 'button']) }}>
    {{ $value ?? $slot }}
</button>

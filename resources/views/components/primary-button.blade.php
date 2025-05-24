@props(['value','type'])

<button {{ $attributes->merge(['class' => 'button button__primary', 'type' => $type ?? 'button']) }}>
    {{ $value ?? $slot }}
</button>

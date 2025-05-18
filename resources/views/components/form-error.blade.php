@props(['messages' => [], 'error' => null])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'form__error']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@elseif($error)
    <div {{ $attributes->merge(['class' => 'form__error']) }}>
        {{$error}}
    </div>
@endif

@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'error' => null
])

<div class="form__group">
    @if($label)
        <x-form-label for="{{$id}}" :value="$label"/>
    @endif

    {{$slot}}

        @if($error)
            <x-form-error class="form__error" :error="$error"/>
        @elseif($name && $errors->has($name))
            <x-form-error class="form__error" :messages="$errors->get($name)"/>
        @endif
</div>

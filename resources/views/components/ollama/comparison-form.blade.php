<form action="{{route('ollama.compare')}}" id="comparison-form" class="form" method="POST">
    @csrf
    <x-form-group :label="__('Enter your prompt')" name="prompt" id="prompt">
        <textarea class="form__textarea" name="prompt" id="prompt" rows="10"></textarea>
        <div class="form__counter">
            <span id="char-count">0</span>/2000
        </div>
    </x-form-group>

    <x-form-group :label="__('Select models')" name="models" id="models">
        <div class="form__model-list">
            @forelse($models as $model)
                <div class="form__model-item">
                    <input type="checkbox" class="form__checkbox" name="models[]" value="{{ $model['name'] }}" id="model-{{$loop->index}}">
                    <label for="model-{{$loop->index}}" class="form__model-label">
                        <span class="form__model-name">{{$model['name']}}</span>
                        <span class="form__model-size">{{$model['size']}}</span>
                    </label>
                </div>

            @empty
                <p class="form__empty-message">{{__('No models available')}}</p>
            @endforelse
        </div>
    </x-form-group>

    <x-primary-button type="submit" class="button__primary">
        {{__('Compare')}} <span class="button__loader" id="loader" style="display: none;"></span>
    </x-primary-button>
</form>



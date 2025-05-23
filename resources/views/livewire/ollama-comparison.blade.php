<div>
    <form wire:submit="compare" class="form">
        <x-form-group :label="__('Enter your prompt')" name="prompt" id="prompt">
            <textarea
                    wire:model.live="prompt"
                    class="form__textarea"
                    id="prompt"
                    rows="10">
            </textarea>
            <div class="form__counter">
                <span id="char-count">{{$this->charCount}}</span>
                /2000
            </div>
        </x-form-group>

        <x-form-group :label="__('Select models')" name="models" id="models">
            <div class="form__model-list">
                @forelse($models as $index => $model)
                    <div class="form__model-item">
                        <input type="checkbox"
                               wire:model.live="selectedModels"
                               class="form__checkbox"
                               name="models[]"
                               value="{{ $model['name'] }}"
                               id="model-{{$index}}"
                        >
                        <label for="model-{{$index}}" class="form__model-label">
                            <span class="form__model-name">{{$model['name']}}</span>
                            <span class="form__model-size">{{$model['size']}}</span>
                        </label>
                    </div>

                @empty
                    <p class="form__empty-message">{{__('No models available')}}</p>
                @endforelse
            </div>
        </x-form-group>

        <x-primary-button type="submit" class="button__primary" wire:loading.attr="disabled">
            {{__('Compare')}}
            <span wire:loading wire:target="compare" class="button__loader"></span>
        </x-primary-button>

    </form>

    <!-- Add after the form -->
    @if($error)
        <div class="alert alert--error mt-4">
            {{ $error }}
        </div>
    @endif

    <div id="results-container" class="results" style="{{ count($results) > 0 ? 'display: block' : 'display: none' }}">
        <h2 class="results__title">{{ __('Results') }}</h2>
        <div class="results__grid">
            @foreach($results as $result)
                <livewire:ollama-model-response
                        :key="'response-'.$result['model']"
                        :model="$result['model']"
                        :duration="$result['total_duration']"
                        :token-count="$result['response_tokens']"
                        :response="$result['response']"
                />
            @endforeach
        </div>
    </div>


</div>

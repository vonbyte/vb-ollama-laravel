<div>

    <form wire:submit="compare;" class="form">
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

        <x-form-group :label="__('Add tags')" id="tags" name="tags">
            <input type="text" class="form__input" name="tags" id="tags" wire:model="tags" placeholder="Add your tags (comma-separated)"/>
        </x-form-group>

        <x-form-group :label="__('Enter your classification notes')" name="notes"  id="notes">
            <input type="text" class="form__input" max="255" name="notes" id="notes" wire:model="notes" placeholder="Add your notes (max 255 chars)"/>
        </x-form-group>

        <x-form-group :label="__('Select models')" name="selectedModels">
            <div class="form__header">
                <span class="form__header-title">{{__('Available Models')}}</span>
                <x-secondary-button
                        type="button"
                        wire:loading.attr="disabled"
                        wire:click="refreshModels"
                        wire:target="refreshModels"
                        title="{{__('Refresh model list')}}"
                >
                    <span wire:loading.remove wire:target="refreshModels">{{__('Refresh')}}</span>
                    <span wire:loading wire:target="refreshModels">{{__('Refreshing')}}</span>

                </x-secondary-button>
            </div>
            <div class="form__model-list">
                @forelse($models as $index => $model)
                    <div class="form__model-item">
                        <input type="checkbox"
                               wire:model.live="selectedModels"
                               class="form__checkbox"
                               name="selectedModels[]"
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

    @if($error)
        <div class="alert alert--error">
            <strong>{{__('Error')}}:</strong> {{$error}}
            @if(str_contains($error, 'Connection') || str_contains($error,'connect'))
                <div class="alert__help">
                    <p>{{__('Please ensure:')}}</p>
                    <ul>
                        <li>{{__('Ollama service is running')}}</li>
                        <li>{{__('Ollama service is accessible on the configured port')}}</li>
                        <li>{{__('At least one model was installed')}}</li>
                    </ul>
                </div>
            @endif
        </div>
    @endif

    @if(count($results) > 0)
        <div id="results-container" class="results">
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
    @endif


</div>

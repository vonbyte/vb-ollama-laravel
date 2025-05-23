<div class="result-card">
    <div class="result-card__header">
        <div class="result-card__header-main">
            <h3 class="result-card__title">{{ $model}}
                @if($size)
                    <span class="result-card__title--small">{{$size}}</span>
                @endif

            </h3>
        </div>
        <div class="result-card__header-metrics">
            <span class="{{$this->timeClass}}" title="{{__('Response time')}}">
                {{ number_format($duration, 2) }}s
            </span>
            @if($tokenCount)
                <span class="result-card__tokens" title="{{__('Token count')}}">
                    {{$tokenCount}} tokens
                </span>
            @endif
        </div>

    </div>
    <div class="result-card__content">
        {!! $this->formattedModelResponse !!}
    </div>
</div>

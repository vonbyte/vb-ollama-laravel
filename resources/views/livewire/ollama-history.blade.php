<div class="results">
    <div class="results__grid">
        @forelse($history as $comparison)
            <div class="result-card">
                <div class="result-card__header">
                    <h3 class="result-card__title">{{Str::limit($comparison->prompt,50,'...',true)}}</h3>
                    <span class="result-card__time">{{$comparison->created_at->diffForHumans()}}</span>
                </div>
                <div class="result-card__content">
                    <div class="history-card">
                        <div class="history-card__models">
                            <span class="content-label">{{__('Models')}}:</span>
                            @foreach($comparison->models as $model)
                                <span class="model-badge">{{$model}}</span>
                            @endforeach
                        </div>

                        <div class="history-card__tags">
                            <span class="content-label">{{__('Tags')}}:</span>
                            @forelse($comparison->tags as $tag)
                                <span class="tag-badge">{{$tag}}</span>
                            @empty
                                ---
                            @endforelse
                        </div>


                        @if($comparison->notes)
                            <div class="history-card__notes">
                                <span class="content-label">{{__('Notes')}}:</span>
                                <p class="notes-text">{{$comparison->notes}}</p>
                            </div>
                        @endif

                        <div class="history-card__results">
                            <span class="content-label">{{ count($comparison->results) }} responses</span>
                            @foreach($comparison->results as $result)
                                <div class="history-card__result-card">
                                    <strong>{{$result['model']}} ({{number_format($result['total_duration'],2)}}s)</strong><br/>
                                    <div class="raw-content">
                                        {!! $this->processResponse($result['response']) !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        @empty
        @endforelse
    </div>
</div>

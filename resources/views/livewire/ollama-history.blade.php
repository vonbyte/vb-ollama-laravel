<div>
    @forelse($history as $comparison)
        <div>
            <div>{{$comparison->prompt}}</div>
            <div>{{ implode(', ',$comparison->models)}}</div>
            @if($comparison->tags)
                <div>{{ implode(', ',$comparison->tags)}}</div>
            @endif
            @if($comparison->notes)
                <div>{{$comparison->notes}}</div>
            @endif

            @foreach($comparison->results as $result)
                <div>
                    <strong>{{$result['model']}} ({{$result['total_duration']}}s)</strong><br/>
                    {{\Illuminate\Support\Str::limit($result['response'],100,'...',true)}}
                </div>
            @endforeach

        </div>
    @empty
    @endforelse
</div>

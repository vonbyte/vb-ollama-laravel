<div>
    @forelse($history as $comparison)
        <div>
            <div>{{$comparison->prompt}}</div>
            <div>{{ implode(', ',$comparison->models)}}</div>
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

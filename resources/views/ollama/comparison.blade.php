<x-layout.app>
<x-ollama.comparison-form :models="$models"/>
    <span>Errors:{{$errors->toJson()}} </span>
    <x-ollama.comparison-results/>

</x-layout.app>






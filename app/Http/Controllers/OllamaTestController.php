<?php

namespace App\Http\Controllers;

use Cloudstudio\Ollama\Facades\Ollama;
use Illuminate\Http\Request;

class OllamaTestController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        $models = Ollama::models()['models'];


        /*$string = "I'm a Laravel developer and my cat Mr. Whiskers keeps sitting directly on my keyboard every time I'm coding. Yesterday he somehow managed to run php artisan migrate:fresh --seed and deleted my entire local database. Today he stepped on my keyboard and committed unfinished code to the main branch with the commit message 'mmmmmmeowwwwwwwkkkk'.
        I need you to explain to me, in the style of official Laravel documentation, why cats are evolutionarily programmed to sabotage developers and what the proper 'Artisan commands' would be to manage a cat in a development environment. Please include code examples of Cat middleware and how to handle feline interruptions in a professional Laravel application.";*/



        $string = "I've been living with my cat Princess Fluffington for 3 years and I still don't understand what she's trying to tell me. She makes different meows throughout the day and I'm convinced there's a pattern.
            Can you help me decode this typical day of cat communications:
            
            5:30 AM: Loud, demanding 'MEOOOOOW'
            7 AM: Soft chirping sounds while staring at birds
            2 PM: Angry chattering at the vacuum cleaner
            6 PM: Excited trilling when I open a can
            11 PM: Weird guttural sounds while bringing me her toy mouse
            
            Please provide a comprehensive translation guide and maybe suggest how I could build a Laravel-powered 'Cat-to-Human Dictionary' API for other confused cat parents.";


        $response = Ollama::agent('You are a creative mentor helping developers with their coding questions, but also with their private everyday struggles.')
            ->prompt($string)
            ->model('deepseek-r1:7b')
            ->options(['temperature' => 0.7])
            ->stream(false)
            ->ask();

        return response()->json([
            'success' => true,
            'models' => $models,
            'response' => $response,
        ]);
    }
}

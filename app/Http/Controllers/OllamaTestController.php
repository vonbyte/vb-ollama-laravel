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

        $string = "I want to develop an AI strategy for the agency where I work. Context:
We are a content marketing agency with departments for Web Development, Data/SEO/Conversion, Customer Success, Content & Creation, and UI/UX. All departments work privately, on the side, sometimes with AI tools like Midjourney, ChatGPT, Claude, etc. The interest is high. Our core platform Hubspot seems to offer AI functions that aren't being utilized. Additionally, we use ClickUp, Google Workspace, Jetbrains IDEs, and VS Code.
Problem: We feel like we're falling behind. AI needs to be introduced in a controlled, legally and ethically sound manner. However, I don't want to look for problems for a solution, as this is certainly the wrong approach.
Task:
What's the best way for me to proceed here? Do you need any additional information to support this?";


        $response = Ollama::agent('You are a strategic thinker in a web agency')
            ->prompt($string)
            ->model('deepseek-r1:1.5b')
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

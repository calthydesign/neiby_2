<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ChatController;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $inputText=$request->food;
        if($inputText!=null) {
            $responseText = $this->generateResponse($inputText);
        
            $messages = [
                ['title' => '体調不良の悩み', 'content' => $inputText],
                ['title' => '体調不良に合った養生方法', 'content' => $responseText]
            ];
            return view('chat.create', ['messages' => $messages]);
        
        }
        return view('chat.create');
    }
 
    public function generateResponse($inputText) {
        $result = OpenAI::completions()->create([
            'model' => 'gpt-3.5-turbo-instruct',
            'prompt' => '私は東洋医学の気血水のうちの気虚の体質です。今の体調不良の内容を伝えます。'.$inputText.'気虚の私に合った養生方法（レシピ、ツボ、食材、過ごし方など）を300字以内で教えてください。',
            'temperature' => 0.8,
            'max_tokens' => 300,
        ]);
        // 結果を改行で分割して見やすくする
        $formattedText = nl2br(trim($result['choices'][0]['text']));
        // <br />を改行に変換
        $formattedText = str_replace('<br />', "\n", $formattedText);
        return $formattedText;
        // return $result['choices'][0]['text'];
    }
}

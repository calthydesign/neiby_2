<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    // チャットページを表示
    public function chat()
    {
        return view('chat.create'); // chat.createビューファイルを返す
    }

    // チャットメッセージを処理
    public function handleChat(Request $request)
    {
        // バリデーション
        $request->validate([
            'food' => 'required|string|max:255',
        ]);

        $inputText = $request->input('food');
        $constructionType = $this->getConstructionType(auth()->user()->construction);
        $responseText = $this->generateResponse($inputText, $constructionType);

        // メッセージを配列に格納
        $messages = [
            ['title' => '体調不良の悩み', 'content' => $inputText],
            ['title' => '体調不良に合った養生方法', 'content' => $responseText]
        ];

        // JSON形式で応答
        return response()->json(['messages' => $messages]);
    }

    // ユーザーのconstructionに基づいてタイプを取得
    private function getConstructionType($construction)
    {
        switch ($construction) {
            case 'kikyo':
                return '気虚';
            case 'kekkyo':
                return '血虚';
            case 'kitai':
                return '気滞';
            case 'oketsu':
                return '瘀血';
            case 'suitai':
                return '水滞';
            default:
                return 'その他';
        }
    }

    public function generateResponse($inputText, $constructionType) {
        $result = OpenAI::completions()->create([
            'model' => 'gpt-3.5-turbo-instruct',
            'prompt' => '私は東洋医学の気血水のうちの' . $constructionType . 'の体質です。今の体調不良の内容を伝えます。' . $inputText . '。' . $constructionType . 'の私に合ったツボを300字以内で箇条書きで教えてください。',
            'temperature' => 0.8,
            'max_tokens' => 300,
        ]);
        
        // 結果を改行で分割して見やすくする
        $formattedText = nl2br(trim($result['choices'][0]['text']));
        // <br />を改行に変換
        $formattedText = str_replace('<br />', "\n", $formattedText);
        return $formattedText;
    }
}
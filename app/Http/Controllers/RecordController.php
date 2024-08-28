<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use Illuminate\Support\Facades\Auth; // Authファサードをインポート

class RecordController extends Controller
{
    public function index()
    {
        // ログインユーザーの投稿を取得
        $posts = Post::where('user_id', Auth::id())->get(); // ユーザーIDでフィルタリング

        // イベントデータを作成
        $events = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->condition, // 体調のタイトル
                'start' => $post->created_at->toISOString(), // 開始日時
                'extendedProps' => [
                    'description' => $post->memo, // メモなどの追加情報
                ],
            ];
        });

        return view('record', compact('events'));
    }
}
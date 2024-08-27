<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // または適切なモデルをインポート

class RecordController extends Controller
{
    public function index()
    {
        $posts = Post::all(); // あなたのPostモデルを使用
    
        $events = Post::all()->map(function ($post) {
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
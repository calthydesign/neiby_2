<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // または適切なモデルをインポート

class RecordController extends Controller
{
    public function index()
    {
        $posts = Post::all(); // あなたのPostモデルを使用
    
        $events = $posts->map(function ($post) {
            return [
                'title' => $post->condition, // 'condition'フィールドを使用
                'start' => $post->created_at->toDateString(), // 日付のみを使用
                'description' => $post->memo, // メモを説明として追加
            ];
        });
        
        // dd($events);
    
        return view('record', compact('events'));
    }
}
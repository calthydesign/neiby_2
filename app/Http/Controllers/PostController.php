<?php
//PostController.php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Construction; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Logを追加


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $user = auth()->user();
    
        if ($user->construction === null) {
            return redirect()->route('diagnoses.index')->with('message', '診断を先に行ってください。');
        }
        $posts = Post::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc')->paginate(3);
        
        $apiKey = env('OPENWEATHER_API_KEY');
        $city = 'Tokyo';
        $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=ja";
        
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody()->getContents(), true);
        $weather = $data['weather'][0]['description'];
        
        $constructionId = null;
        
        switch ($user->construction) {
            case 'kikyo':
                $constructionId = 1;
                break;
            case 'kitai':
                $constructionId = 2;
                break;
            case 'kekkyo':
                $constructionId = 3;
                break;
            case 'oketsu':
                $constructionId = 4;
                break;
            case 'suitai':
                $constructionId = 5;
                break;
        }
        
        $construction = $constructionId ? Construction::find($constructionId) : null;
    
        // カレンダー用のイベントデータを作成
        $events = Post::where('user_id', Auth::id())->get()->map(function ($post) {
            return [
                'id' => $post->id, // IDを追加
                'title' => $post->condition,
                'start' => $post->created_at->format('Y-m-d'),
                'extendedProps' => [
                    'description' => $post->memo, // 説明を追加
                ],
            ];
        });
    
        return view('posts', compact('posts', 'weather', 'construction', 'events'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーションを追加
        $validator = Validator::make($request->all(), [
            'condition' => 'required',
        ]);
    
        // バリデーションが失敗した場合
        if ($validator->fails()) {
            return redirect()->route('posts.index')
                ->withErrors($validator)
                ->withInput();
        }
        $apiKey = env('OPENWEATHER_API_KEY');
        $city = 'Tokyo';
        $url = "http://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric&lang=ja";
    
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody()->getContents(), true);
        $weather = $data['weather'][0]['description']; // 天気情報を取得
    
        $post = new Post;
        $post->condition = $request->condition;
        $post->memo = $request->memo;
        $post->weather = $weather; // 天気情報を保存
        $post->selected_tags = is_array($request->selected_tags) ? implode(',', $request->selected_tags) : ''; // 配列であることを確認してからimplode
        // selected_tags の処理を修正
        $selectedTags = $request->selected_tags;
        if (is_array($selectedTags)) {
            $post->selected_tags = implode(',', $selectedTags);
        } elseif (is_string($selectedTags) && !empty($selectedTags)) {
            $post->selected_tags = $selectedTags;
        } else {
            $post->selected_tags = '';
        }
        // dd($request->selected_tags);
        $post->user_id = auth()->id(); // 認証されたユーザーのIDを設定
        $post->save();
        // dd($post->fresh());  // 保存後に最新のデータを取得してダンプ
    
        return redirect()->route('posts.index')->with('success', '投稿が保存されました。');
    }
        /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }
        
   
    /**
     * Show the form for editing the specified resource.
     */
    
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $selectedTags = explode(',', $post->selected_tags); // 保存されているタグを配列に変換

        $user = auth()->user();
        $constructionId = null;
        
        switch ($user->construction) {
            case 'kikyo':
                $constructionId = 1;
                break;
            case 'kitai':
                $constructionId = 2;
                break;
            case 'kekkyo':
                $constructionId = 3;
                break;
            case 'oketsu':
                $constructionId = 4;
                break;
            case 'suitai':
                $constructionId = 5;
                break;
        }
        
        $construction = $constructionId ? Construction::find($constructionId) : null;
    
        // カレンダー用のイベントデータを作成
        $events = Post::where('user_id', Auth::id())->get()->map(function ($post) {
            return [
                'title' => $post->condition,
                'start' => $post->created_at->format('Y-m-d'),
                'url' => route('posts.edit', $post->id)
            ];
        });
    

        return view('postsedit', compact('post', 'selectedTags', 'construction', 'events'));
    }

    public function update(Request $request, $id)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'condition' => 'required|string|max:255',
            'memo' => 'required|string|max:255',
        ]);

        // バリデーション:エラー
        if ($validator->fails()) {
            return redirect()->route('posts.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }
        
        // データ更新
        $post = Post::where('user_id', Auth::user()->id)->findOrFail($id);
        $post->condition = $request->condition;
        $post->memo = $request->memo;
        $post->selected_tags = $request->input('selected_tags', []);// タグの更新
        $post->save();

        return redirect()->route('posts.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();       //追加
        return redirect('/');  //追加
    }
}

    <!-- resources/views/posts.blade.php -->
    <head>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    </head>
    <x-app-layout>
        
    
        <!--ヘッダー[START]-->
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <form action="{{ route('posts.index') }}" method="GET" class="w-full max-w-screen-md mx-auto">
                    <x-button class="bg-gray-100 text-gray-900">{{ __('Dashboard') }}</x-button>
                </form>
            </h2>
        </x-slot>
        <!--ヘッダー[END]-->
                
        <!-- バリデーションエラーの表示に使用-->
        <x-errors id="errors" class="bg-blue-950 rounded-lg">{{$errors}}</x-errors> <!-- 色変更 -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- バリデーションエラーの表示に使用-->
        
        <!--全エリア[START]-->
        <div class="flex flex-col bg-gray-100">
    
            <!-- 上エリア[START]--> 
                <!-- 体質別コンテンツの挿入[START] -->
                    @include('kiketsusui.construction', ['construction' => $construction])
                <!-- 体質別コンテンツの挿入[END] -->
            
            <div class="text-gray-700 text-left px-4 py-4 m-2 w-full max-w-screen-md mx-auto">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-500 font-bold">
                        体調を記録する
                    </div>
                </div>
                <!-- 天気情報の表示 -->
                <div class="overflow-hidden">
                    <div class="p-4">
                        今日の天気: {{ $weather }}
                    </div>
                </div>
                <!-- メモ -->
                <form action="{{ route('posts.store') }}" method="POST">
                    @csrf
                    <!-- ラジオボタンの部分 -->
                    <div class="w-full px-3 mb-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                            今日の調子
                            <span class="text-xs text-gray-400">※入力必須</span>
                        </label>
                        <div class="flex flex-wrap">
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="condition" value="😄とてもよい" class="form-radio">
                                <span class="ml-2">😄とてもよい</span>
                            </label>
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="condition" value="😊よい" class="form-radio">
                                <span class="ml-2">😊よい</span>
                            </label>
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="condition" value="🙂普通" class="form-radio">
                                <span class="ml-2">🙂普通</span>
                            </label>
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="condition" value="😒イマイチ" class="form-radio">
                                <span class="ml-2">😒イマイチ</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="condition" value="😫悪い" class="form-radio">
                                <span class="ml-2">😫悪い</span>
                            </label>
                        </div>
                    </div>
                
                    <!-- テキスト入力の部分 -->
                    
                    <div class="w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                            気づいたことメモ
                        </label>
                        <input name="memo" class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" type="text" placeholder="">
                    </div>
                    <!-- タグ送信のための隠しフィールド -->
                    <input type="hidden" name="selected_tags" id="selected_tags" value="">
                    
                    <!-- デバッグ用 -->
                    <!--<div>-->
                    <!--    <p class="my-4">Selected Tags: <span id="debugTags"></span></p>-->
                    <!--</div>-->
                
                    <!-- 送信ボタン -->
                    <div class="flex justify-center">
                        <button type="submit" class="bg-blue-950 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"> <!-- 色変更 -->
                            送信
                        </button>
                    </div>
                </form>
            </div>
            <!--上エリア[END]--> 
        
        
        <!--下側エリア[START]-->
        <div class="text-gray-700 text-left bg-blue-100 px-4 py-2 m-2 w-full max-w-screen-md mx-auto">
             <!-- カレンダー表示 -->
            <div id="calendar"></div>
            
        </div>
        <!--下側エリア[[END]-->       
    
    </div>
     <!--全エリア[END]-->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        //カレンダー表示
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          events: @json($events)
        });
        calendar.render();
      });
    
    </script>
    </x-app-layout>
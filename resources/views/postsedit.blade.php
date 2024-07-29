<!-- resources/views/postedit.blade.php -->
<x-app-layout>
    <head>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
    </head>

    <!--ヘッダー[START]-->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <form action="{{ route('posts.index') }}" method="GET" class="w-full max-w-screen-md mx-auto">
                <x-button class="bg-gray-100 text-gray-900">{{ __('Dashboard') }}：更新画面</x-button>
            </form>
        </h2>
    </x-slot>
    <!--ヘッダー[END]-->
            
    <!-- バリデーションエラーの表示に使用-->
    <x-errors id="errors" class="bg-blue-950 rounded-lg">{{$errors}}</x-errors>
    <!-- バリデーションエラーの表示に使用-->
    
    <!--全エリア[START]-->
    <div class="flex flex-col bg-gray-100">

        <!-- 上エリア[START]-->
        
        <div class="text-gray-700 text-left px-4 py-4 m-2 w-full max-w-screen-md mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-500 font-bold">
                    体調を更新する
                </div>
            </div>
            <!-- 天気情報の表示 -->
            <div class="overflow-hidden">
                <div class="p-4">
                    その日の天気: {{ $post->weather }}
                </div>
            </div>
            <!-- 登録日時と更新日時 -->
            <div class="mb-4">
                <div class="text-sm text-gray-600">
                    登録日時: {{ $post->created_at->format('Y-m-d H:i:s') }}
                </div>
                <div class="text-sm text-gray-600">
                    更新日時: {{ $post->updated_at->format('Y-m-d H:i:s') }}
                </div>
            </div>
            <!-- メモ -->
            <form action="{{ route('posts.update', $post->id) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- ラジオボタンの部分 -->
                <div class="w-full px-3 mb-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        今日の調子
                    </label>
                    <div class="flex flex-wrap">
                        @php
                        $conditions = ['😄とてもよい', '😊よい', '🙂普通', '😒イマイチ', '😫悪い'];
                        @endphp
                        @foreach ($conditions as $condition)
                            <label class="inline-flex items-center mr-4">
                                <input type="radio" name="condition" value="{{ $condition }}" class="form-radio" {{ $post->condition == $condition ? 'checked' : '' }}>
                                <span class="ml-2">{{ $condition }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            
                <!-- テキスト入力の部分 -->
                <div class="w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        気づいたことメモ
                    </label>
                    <input name="memo" value="{{ $post->memo }}" class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" type="text" placeholder="">
                </div>
                
                <h3 class="text-lg font-semibold text-center font-roboto mt-4">実践した習慣を修正（青が実践したもの）</h3>
                @include('kiketsusui.tag_selection', ['construction' => $construction, 'selectedTags' => $selectedTags])
                
                <!--タグ送信用隠しフィールド-->
                <input type="hidden" name="selected_tags" id="selected_tags" value="{{ is_array($selectedTags) ? implode(',', $selectedTags) : $selectedTags }}">            
                <!-- 更新ボタン -->
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-950 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        更新
                    </button>
                </div>
            </form>
            <!--削除ボタンを追加 -->
                <div class="flex justify-center mt-4">
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-400 text-white font-bold py-2 px-4 rounded">
                            削除
                        </button>
                    </form>
                </div>
        </div>
        <!--上エリア[END]--> 
        
        <!--下側エリア[START]-->
        <div class="text-gray-700 text-left bg-blue-100 px-4 py-2 m-2 w-full max-w-screen-md mx-auto">
             <!-- カレンダー表示 -->
            <div id="calendar" style="height: 600px;"></div>
        </div>
        <!--下側エリア[[END]-->       
    
    </div>
    <!--全エリア[END]-->
    
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          events: @json($events)
        });
        calendar.render();
      });
    </script>
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedTags = @json($selectedTags);
    const tagButtons = document.querySelectorAll('button[name="selected_tags"]');
    
    // 初期状態で選択済みのタグをハイライト
    tagButtons.forEach(button => {
        if (selectedTags.includes(button.value)) {
            button.classList.remove('bg-blue-200', 'text-blue-800');
            button.classList.add('bg-blue-800', 'text-white');
        }
    });

    tagButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const tagValue = this.value;
            const index = selectedTags.indexOf(tagValue);
            if (index > -1) {
                selectedTags.splice(index, 1);
                this.classList.remove('bg-blue-800', 'text-white');
                this.classList.add('bg-blue-200', 'text-blue-800');
            } else {
                selectedTags.push(tagValue);
                this.classList.remove('bg-blue-200', 'text-blue-800');
                this.classList.add('bg-blue-800', 'text-white');
            }
            updateHiddenInput();
        });
    });

    function updateHiddenInput() {
        const hiddenInput = document.getElementById('selected_tags');
        hiddenInput.value = selectedTags.join(',');
    }

    // 初期状態でhidden inputを更新
    updateHiddenInput();
});
</script>
</x-app-layout>
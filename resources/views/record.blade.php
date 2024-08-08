<style>
.fc-event {
    cursor: pointer;
}
.fc-description {
    font-size: 0.8em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<x-app-layout>
    <!--ヘッダー[START]-->
    
    <!--ヘッダー[END]-->
            
    <!-- バリデーションエラーの表示に使用-->
    <x-errors id="errors" class="bg-blue-950 rounded-lg">{{$errors}}</x-errors>
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
    <div class="flex flex-col bg-gray-100 mb-12">

        <!-- 上エリア[START]--> 
        
        <div class="text-gray-700 text-left px-4 py-4 m-2 w-full max-w-screen-md mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-500 font-bold">
                    体調記録
                </div>
            </div>
            
            <!-- カレンダー表示 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 bg-white border-b border-gray-500">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <!--上エリア[END]--> 
        
        <!-- モーダルのトリガーボタンを画面下部に固定 -->
        <div class="fixed bottom-20 right-4">
            <button id="open-modal" class="bg-blue-500 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-600 transition duration-200">よもぎ先生BOTに聞く</button>
        </div>
        
        <!-- モーダル本体 -->
        <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md sm:max-w-lg md:max-w-2xl">
                <h2 class="text-lg font-bold mb-4">よもぎ先生BOTに相談してみよう</h2>
                <div id="modal-content">
                    <!-- chat.blade.phpの内容をここに挿入 -->
                    @include('chat.create')
                </div>
                <div class="flex justify-end mt-4">
                    <button id="close-modal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">×</button>
                </div>
            </div>
        </div>

    </div>
    <!--全エリア[END]-->

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        //カレンダー
        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var events = @json($events);  // PHPの配列をJavaScriptのオブジェクトに変換
        console.log(events);  // デバッグ用：コンソールでイベントデータを確認
    
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: events,
            eventContent: function(arg) {
                return {
                    html: '<div class="fc-content">' +
                          '<span class="fc-title">' + arg.event.title + '</span>' +
                          '<div class="fc-description">' + arg.event.extendedProps.description + '</div>' +
                          '</div>'
                };
            }
        });
        calendar.render();
    });

        // モーダル
        const modal = document.getElementById('modal');
        const openModalButton = document.getElementById('open-modal');
        const closeModalButton = document.getElementById('close-modal');

        // モーダルを開く
        openModalButton.addEventListener('click', function() {
            modal.classList.remove('hidden');
        });

        // モーダルを閉じる
        closeModalButton.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // モーダルの外側をクリックしたときに閉じる
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
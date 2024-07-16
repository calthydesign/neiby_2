<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <h1 class="my-4 text-l font-bold">よもぎ先生AIチャット</h1>
            {{-- 症状を入力するためのフォーム --}}
            <p>気になる症状はありますか？</p>
            <form action="{{route('chat.post')}}" method="POST">
                @csrf
                <input name="food" type="text">
                <div>
                <button type="submit" style="padding:0.4em;background-color:green;color:white;">
                    送信する
                </button>
                <button id="clearChatButton" style="padding:0.4em;background-color:red;color:white;">
                    履歴削除
                </button>
            </div>
            </form>
                {{--ChatGPTの回答を表示 --}}
                @isset($messages)
                <div id="chat-contents">
                    @foreach($messages as $message)
                        <div>
                           {{ $message['title'] }}: {{ $message['content'] }}
                        </div>
                    @endforeach
                </div>
                @endisset
            </div>
        </div>
        
    {{-- 履歴削除ボタンをクリックすると、ChatGPTの回答（chat-contents内）を削除するスクリプト --}}
    <script>
        document.getElementById('clearChatButton').addEventListener('click', function() {
            const chatContainer = document.getElementById('chat-contents');
            chatContainer.innerHTML = '';
        });
    </script>

            </div>
        </div>
    </div>
</x-app-layout>
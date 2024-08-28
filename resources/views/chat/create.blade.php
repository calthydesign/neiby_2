    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-y-auto shadow-sm sm:rounded-lg">
                <h1 class="my-4 text-l font-bold">よもぎ先生AIチャット</h1>
                <p>気になる症状はありますか？</p>
                <form id="chat-form">
                    @csrf
                    <input name="food" type="text" required>
                    <div>
                        <button type="submit" style="padding:0.4em;background-color:blue;color:white;">
                            送信する
                        </button>
                        <button id="clearChatButton" type="button" style="padding:0.4em;background-color:gray;color:white;">
                            履歴削除
                        </button>
                    </div>
                </form>
                <div id="loading" class="hidden text-center">
                    <p>処理中...</p>
                    <div class="loader"></div> <!-- ローディングアニメーション -->
                </div>
                <div id="chat-contents" class="h-128 overflow-y-auto">
                    <!-- チャット内容がここに入ります -->
                </div>
            </div>
        </div>
    </div>
    
    <!--ローディングアニメーション-->
    <style>
         .loader {
        border: 8px solid #f3f3f3; /* Light grey */
        border-top: 8px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto; /* 中央に配置 */
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>

        <script>
            document.getElementById('chat-form').addEventListener('submit', function(event) {
                event.preventDefault(); // フォームのデフォルトの送信を防ぐ

                const formData = new FormData(this);
                const csrfToken = document.querySelector('input[name="_token"]').value;

                fetch("{{ route('chat.post') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const chatContainer = document.getElementById('chat-contents');
                    chatContainer.innerHTML = ''; // 既存のメッセージをクリア
                    data.messages.forEach(message => {
                        chatContainer.innerHTML += `<div><strong>${message.title}</strong>: ${message.content}</div>`;
                    });
                })
                .catch(error => console.error('Error:', error));
            });

            document.getElementById('clearChatButton').addEventListener('click', function() {
                const chatContainer = document.getElementById('chat-contents');
                chatContainer.innerHTML = '';
            });
            
            //ローディングアニメーション
        document.getElementById('chat-form').addEventListener('submit', function(event) {
        event.preventDefault(); // フォームのデフォルトの送信を防ぐ
    
        const formData = new FormData(this);
        const csrfToken = document.querySelector('input[name="_token"]').value;
    
        // ローディングインジケーターを表示
        document.getElementById('loading').classList.remove('hidden');
    
        fetch("{{ route('chat.post') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const chatContainer = document.getElementById('chat-contents');
            chatContainer.innerHTML = ''; // 既存のメッセージをクリア
            data.messages.forEach(message => {
                chatContainer.innerHTML += `<div><strong>${message.title}</strong>: ${message.content}</div>`;
            });
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            // ローディングインジケーターを非表示
            document.getElementById('loading').classList.add('hidden');
        });
    });
        </script>

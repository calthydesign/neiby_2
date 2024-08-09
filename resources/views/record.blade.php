<x-app-layout>
    <head>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.jsの読み込み -->
    </head>
    <style>
        .fc-event {
            cursor: pointer;
            pointer-events: auto; /* クリックを有効にする */
        }
        .fc-description {
            font-size: 0.8em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <div class="flex flex-col bg-gray-100 mb-20">
        <div class="text-gray-700 text-left px-4 py-4 m-2 w-full max-w-screen-md mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-500 font-bold">
                    体調記録
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 bg-white border-b border-gray-500">
                    <div id="calendar"></div>
                </div>
            </div>
            <!-- 折れ線グラフの表示エリア -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 bg-white border-b border-gray-500">
                    <canvas id="lineChart"></canvas> <!-- 折れ線グラフ用のキャンバス -->
                </div>
            </div>
        </div>
    </div>

    <script>
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
                          '<div class="fc-description">' + (arg.event.extendedProps.description || '') + '</div>' +
                          '</div>'
                };
            },
            eventClick: function(info) {
                var eventId = info.event.id; // イベントのIDを取得
                if (eventId) { // IDが存在する場合のみリダイレクト
                    window.location.href = '/posts/edit/' + eventId; // 適切なURLに変更
                } else {
                    console.error('Event ID is not defined');
                }
            }
        });
        calendar.render();

        // 折れ線グラフのデータを準備
        var labels = []; // 日付のラベル
        var dataPoints = []; // データポイント

        // イベントからデータを抽出
        events.forEach(event => {
            var date = event.start; // 日付
            var condition = event.title; // 体調

            // 日付をラベルに追加
            labels.push(date);

            // 体調に応じたスコアを計算
            var score = 0;
            switch (condition) {
                case '😄とてもよい':
                    score = 2;
                    break;
                case '😊よい':
                    score = 1;
                    break;
                case '🙂普通':
                    score = 0;
                    break;
                case '😒イマイチ':
                    score = -1;
                    break;
                case '😫悪い':
                    score = -2;
                    break;
            }
            dataPoints.push(score);
        });

        // 折れ線グラフを描画
        var ctx = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '体調の変化',
                    data: dataPoints,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false // 塗りを無効にする
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                switch (value) {
                                    case 2: return '😄';
                                    case 1: return '😊';
                                    case 0: return '🙂';
                                    case -1: return '😒';
                                    case -2: return '😫';
                                    default: return '';
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: '体調'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: '日付'
                        }
                    }
                }
            }
        });
    });
</script>
</x-app-layout>
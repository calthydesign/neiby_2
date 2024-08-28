<x-app-layout>
    <head>
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.jsの読み込み -->
        <script src="https://cdn.jsdelivr.net/npm/moment"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
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
                    html: '<div class="fc-content bg-blue-500 text-white p-2 rounded-lg shadow-md cursor-pointer overflow-hidden" style="white-space: nowrap; text-overflow: ellipsis; width: 100%;">' +
                          '<span class="fc-title">' + arg.event.title + '</span>' +
                          '<div class="fc-description">' + (arg.event.extendedProps.description || '') + '</div>' +
                          '</div>'
                };
            },
            eventClick: function(info) {
                var eventId = info.event.id; // イベントのIDを取得
                console.log('Event ID:', eventId); // 追加
                if (eventId) { // IDが存在する場合のみリダイレクト
                    window.location.href = '{{ url("posts/edit") }}/' + eventId; // 適切なURLに変更
                } else {
                    console.error('Event ID is not defined');
                }
            }
        });
        calendar.render();

            // 折れ線グラフのデータを準備
            events.sort((a, b) => new Date(b.start) - new Date(a.start)); // 降順にソート
        
            var labels = [];
            var dataPoints = [];
            var processedDates = {};
        
            events.forEach(event => {
                var date = new Date(event.start);
                var dateString = date.toISOString().split('T')[0]; // YYYY-MM-DD 形式の文字列
        
                // その日の記録がまだ処理されていない場合のみ処理する
                if (!processedDates[dateString]) {
                    labels.push(dateString);
                    var condition = event.title;
        
                    // 体調に応じたスコアを計算
                    var score = 0;
                    switch (condition) {
                        case '😄とてもよい': score = 2; break;
                        case '😊よい': score = 1; break;
                        case '🙂普通': score = 0; break;
                        case '😒イマイチ': score = -1; break;
                        case '😫悪い': score = -2; break;
                    }
                    dataPoints.push(score);
        
                    // この日付を処理済みとしてマーク
                    processedDates[dateString] = true;
                }
            });
        
            // ラベルと数値を日付順（昇順）にソート
            var sortedData = labels.map((label, index) => ({ label, value: dataPoints[index] }))
                .sort((a, b) => new Date(a.label) - new Date(b.label));
        
            labels = sortedData.map(item => item.label);
            dataPoints = sortedData.map(item => item.value);
        
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
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                parser: 'YYYY-MM-DD',
                                unit: 'day',
                                displayFormats: {
                                    day: 'MM/DD'
                                }
                            },
                            title: {
                                display: true,
                                text: '日付'
                            },
                            ticks: {
                                source: 'labels',
                                autoSkip: true, // 自動でスキップ
                                maxTicksLimit: 10, // 最大表示数を制限
                                maxRotation: 45, // 最大回転角度
                                minRotation: 30 // 最小回転角度
                            }
                        },
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
                        }
                    }
                }
            });
        });
    
    console.log(events);
    </script>
</x-app-layout>
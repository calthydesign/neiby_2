<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">診断</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-blue-200">
                    <form id="diagnosisForm" action="{{ route('diagnoses.store') }}" method="POST">
                        @csrf
                        <div class="question-slider">
                            <div class="slide-container">
                                <div class="slide" id="slide1">
                                    @include('questions.1')
                                    <button type="button" class="next-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">次へ</button>
                                </div>
                                <div class="slide" id="slide2">
                                    @include('questions.2')
                                    <button type="button" class="next-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">次へ</button>
                                </div>
                                <div class="slide" id="slide3">
                                    @include('questions.3')
                                    <button type="button" class="next-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">次へ</button>
                                </div>
                                <div class="slide" id="slide4">
                                    @include('questions.4')
                                    <button type="button" class="next-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">次へ</button>
                                </div>
                                <div class="slide" id="slide5">
                                    @include('questions.5')
                                    <button type="button" class="next-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">次へ</button>
                                </div>
                                <div class="slide" id="slide6">
                                    @include('questions.6')
                                <button type="button" class="diagnose-btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">診断する</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="flex justify-center">
                <div id="result" class="hidden mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg w-full max-w-md">
                    <div class="p-6 bg-white border-b border-blue-200">
                        <h2 class="text-xl font-bold mb-4">診断結果</h2>
                        <p id="diagnosis-result" class="mb-4"></p>
                        <canvas id="chart-container" class="mb-6"></canvas>
                        <div class="text-center">
                            <a href="{{ route('posts.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                体質に合った習慣をみる
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        let chart;

        function showSlide(n) {
            slides.forEach((slide, index) => {
                slide.style.display = index === n ? 'block' : 'none';
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', nextSlide);
            btn.classList.add('bg-blue-300', 'hover:bg-blue-400', 'text-blue-800', 'font-bold', 'py-2', 'px-4', 'rounded', 'mr-2');
        });

        document.querySelector('.diagnose-btn').addEventListener('click', diagnose);

        function diagnose() {
        document.querySelector('.question-slider').style.display = 'none';
        document.getElementById('result').classList.remove('hidden');
        window.scrollTo(0, 0);
        
        const form = document.getElementById('diagnosisForm');
    
        let counts = {
            kekkyo: 0, kikyo: 0, kitai: 0, oketsu: 0, suitai: 0
        };
    
        form.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
            checkbox.value.split('_').forEach(value => {
                if (counts.hasOwnProperty(value)) {
                    counts[value]++;
                }
            });
        });
    
        const maxCategory = Object.entries(counts).reduce((a, b) => a[1] > b[1] ? a : b)[0];
        const categoryNames = {
            kekkyo: '血虚', kikyo: '気虚', kitai: '気滞', oketsu: '瘀血', suitai: '水滞'
        };
    
        drawChart(counts);
        document.getElementById('diagnosis-result').textContent = ` ${categoryNames[maxCategory]}の傾向があります`;
        document.getElementById('result').classList.remove('hidden');
        
        const formData = {
            counts: counts,
            result: maxCategory
        };
    
        fetch('{{ route("diagnoses.store") }}', {
            method: 'POST',
            body: JSON.stringify(formData),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Results saved:', data);
            if (data.success) {
                alert('診断結果が保存されました。');
            } else {
                alert('診断結果の保存に失敗しました。: ' + (data.message || ''));
            }
        })
        .catch(error => {
            console.error('Error saving results:', error);
            alert('エラーが発生しました。もう一度お試しください。エラー: ' + error.message);
        });
    }
        function drawChart(counts) {
            const ctx = document.getElementById('chart-container');
            if (chart) {
                chart.destroy();
            }
            chart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['気虚', '血虚', '気滞', '瘀血', '水滞'],
                    datasets: [{
                        label: '診断結果',
                        data: [counts.kikyo, counts.kekkyo, counts.kitai, counts.oketsu, counts.suitai],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgb(255, 99, 132)',
                        pointBackgroundColor: 'rgb(255, 99, 132)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(255, 99, 132)'
                    }]
                },
                options: {
                    elements: {
                        line: { borderWidth: 3 }
                    }
                }
            });
        }

        showSlide(currentSlide);
    </script>
</x-app-layout>
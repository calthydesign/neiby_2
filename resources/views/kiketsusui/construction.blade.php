<!-- resources/views/kiketsusui.construction.blade.php -->
<div class="flex flex-col items-center p-4 w-full max-w-screen-md mx-auto">
    <div>
        <h1 class="text-xl font-bold text-center font-roboto mt-4">
            @php
                $constructionType = '';
                switch(auth()->user()->construction) {
                    case 'kikyo':
                        $constructionType = '気虚';
                        break;
                    case 'kekkyo':
                        $constructionType = '血虚';
                        break;
                    case 'kitai':
                        $constructionType = '気滞';
                        break;
                    case 'oketsu':
                        $constructionType = '瘀血';
                        break;
                    case 'suitai':
                        $constructionType = '水滞';
                        break;
                    default:
                        $constructionType = 'その他';
                }
            @endphp
            {{ $constructionType }}タイプ
         </h1>
        <p class="text-justify font-roboto mt-4 leading-relaxed">
            {{ $construction->description }}
        </p>
    </div>
    
    <div class="w-full bg-white p-4 mt-4">
        <h2 class="text-lg font-semibold text-center font-roboto">
            あなたへのおすすめ習慣
        </h2>
        <p class="text-xs text-center font-roboto mt-2">
            タグをクリックして保存できます
        </p>
        <div class="my-8">
            <h3 class="text-lg font-semibold text-center font-roboto mt-4">
                食事
            </h3>
            <p class="text-base text-center font-roboto mt-2">食べた方がいいもの</p>
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                <div class="flex flex-wrap mt-2">
                    @php
                    $categories = [
                        'meal_vegetables' => '野菜',
                        'meal_fruits' => '果物',
                        'meal_fish_meat' => '魚・肉',
                        'meal_seasonings' => '調味料',
                        'meal_dried_goods' => '乾物',
                        'meal_tea' => 'お茶'
                    ];
                    @endphp
                    @foreach ($categories as $category => $displayName)
                        <h3 class="text-sm font-semibold w-full m-2 text-left text-gray-400">{{ $displayName }}</h3> <!-- カテゴリ名 -->
                        @php
                        $tags = explode('、', $construction->{$category});
                        @endphp
                        @foreach ($tags as $tag)
                            <button type="button" name="selected_tag" value="{{ $tag }}" class="text-sm m-1 p-2 bg-blue-200 text-blue-800 rounded-full cursor-pointer font-roboto">
                                {{ $tag }}
                            </button>
                        @endforeach
                        <hr class="border-t-2 border-dashed border-slate-100 my-1 w-full">
                    @endforeach
                </div>
            </form>
            <h3 class="text-base font-semibold text-center font-roboto mt-4">
                食生活についてのアドバイス
            </h3>
            <p class="text-base text-justify font-roboto mt-2">
                {{ $construction->meal_recommendation }}
            </p>
        </div>
        
        <div class="my-8">
            <h3 class="text-lg font-semibold text-center font-roboto mt-4">
                運動
            </h3>
            <p class="text-base text-center font-roboto mt-2">おすすめの運動</p>
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                <div class="flex flex-wrap mt-2">
                    @php
                    $tags = explode('、', $construction->exercise);
                    @endphp
                    @foreach ($tags as $tag)
                        <button type="button" name="selected_tag" value="{{ $tag }}" class="text-sm m-1 p-2 bg-blue-200 text-blue-800 rounded-full cursor-pointer font-roboto">
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>
            </form>
            <h3 class="text-base font-semibold text-center font-roboto mt-4">
                運動についてのアドバイス
            </h3>
            <p class="text-base text-justify font-roboto mt-2">
                {{ $construction->exercise_recommendation }}
            </p>
        </div>
        
        <div class="my-8">
            <h3 class="text-lg font-semibold text-center font-roboto mt-4">
                ライフスタイル
            </h3>
            <p class="text-base text-center font-roboto mt-2">日常でのおすすめの習慣</p>
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                <div class="flex flex-wrap mt-2">
                    @php
                    $tags = explode('、', $construction->lifestyle);
                    @endphp
                    @foreach ($tags as $tag)
                        <button type="button" name="selected_tag" value="{{ $tag }}" class="text-sm m-1 p-2 bg-blue-200 text-blue-800 rounded-full cursor-pointer font-roboto">
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>
            </form>
            <h3 class="text-base font-semibold text-center font-roboto mt-4">
                日常生活についてのアドバイス
            </h3>
            <p class="text-base text-justify font-roboto mt-2">
                {{ $construction->lifestyle_recommendation }}
            </p>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedTags = []; // 選択されたタグを保持する配列
    const tagButtons = document.querySelectorAll('button[name="selected_tag"]');
    tagButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const tagValue = this.value;
            const index = selectedTags.indexOf(tagValue);
            if (index > -1) {
                selectedTags.splice(index, 1); // 既に存在する場合は削除
                this.classList.remove('bg-blue-800', 'text-white'); // クラスを削除
                this.classList.add('bg-blue-200', 'text-blue-800'); // 元のクラスを追加
            } else {
                selectedTags.push(tagValue); // 存在しない場合は追加
                this.classList.remove('bg-blue-200', 'text-blue-800'); // 元のクラスを削除
                this.classList.add('bg-blue-800', 'text-white'); // 新しいクラスを追加
            }
            updateHiddenInput();
        });
    });

    function updateHiddenInput() {
        const hiddenInput = document.getElementById('selected_tags');
        hiddenInput.value = selectedTags.join(','); // 配列をカンマ区切りの文字列に変換
    }
});
</script>
<!-- resources/views/kiketsusui/tag_selection.blade.php -->
<div class="flex flex-wrap mt-2">
    @php
    $fields = [
        'meal_vegetables' => '野菜',
        'meal_fruits' => '果物',
        'meal_fish_meat' => '魚・肉',
        'meal_seasonings' => '調味料',
        'meal_dried_goods' => '乾物',
        'meal_tea' => 'お茶',
        'exercise' => '運動',
        'lifestyle' => 'ライフスタイル',
    ];
    @endphp

    @foreach ($fields as $field => $displayName)
        @php
        $tags = explode('、', $construction->$field); // 各カラムの値を取得
        @endphp
        <div class="w-full mt-4">
            <h3 class="font-bold text-lg">{{ $displayName }}</h3> <!-- カラムの表示名 -->
            @foreach ($tags as $tag)
                <button type="button" name="selected_tags" value="{{ $tag }}" class="m-1 p-2 text-sm {{ in_array($tag, $selectedTags) ? 'bg-blue-800 text-white' : 'bg-blue-200 text-blue-800' }} rounded-full cursor-pointer font-roboto">
                    {{ $tag }}
                </button>
            @endforeach
        </div>
    @endforeach
</div>
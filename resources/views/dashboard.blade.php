<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px); /* 下からスライドイン */
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.slide-in {
    opacity: 0; /* 初めは非表示 */
    animation: slideIn 1s forwards; /* 1秒でスライドイン */
}

.delay-0 {
    animation-delay: 0s; /* 最初のspan */
}

.delay-2000 {
    animation-delay: 2s; /* 2秒後に表示 */
}

.delay-4000 {
    animation-delay: 4s; /* 4秒後に表示 */
}
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Neiby ホーム') }}
        </h2>
    </x-slot>

    <!--<div class="py-12">-->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <!--        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">-->
    <!--            <div class="p-6 text-gray-900">-->
    <!--               }-->
    <!--            </div>-->
    <!--        </div>-->
            
        <!--説明スライド-->
    <!--    <div class="my-4">-->
    <!--        <p>アプリの説明は<a href="https://www.canva.com/design/DAGKKp9FRpM/XHM6Nq4_AWPrwo2EXMrlRg/view?utm_content=DAGKKp9FRpM&utm_campaign=designshare&utm_medium=link&utm_source=editor" class="text-blue-500 underline">こちら</a></p>-->
    <!--    </div>-->
        
        <!--よもぎAIBOT-->
        <div class="m-8 p-4 bg-white rounded-xl">
            <p>こんにちは！よもぎ先生です。<br>
                <span class="slide-in delay-0">このアプリは、東洋医学における「未病」改善を目指すサービスを提供しています。<br></span>
                <span class="slide-in delay-2000">「病院に行っても問題ないけどずっと体調不良がある」「体質なのか不調があるけど諦めている」そんな方をサポートしていきます！<br></span>
                <span class="slide-in delay-4000">はじめに、あなたはこれからどうしたいか教えてもらえますか？</span>
            </p>
        </div>
        
        <div class="mt-6 p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <h2 class="font-bold">いますぐ専門家に相談したい</h2>
            <p>→<a href="https://www.google.com/maps/search/鍼灸師/@?hl=ja" target="_blank" class="text-blue-500 underline">お近くの鍼灸師検索</a></p>
            <p>→<a href="#" class="text-blue-500 underline" target="_blank">よもぎ先生にオンライン相談</a></p>
        </div>
        
        <div class="mt-6 p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <h2 class="font-bold">自分の体調について知りたい、自分で改善してみたい</h2>
                <p>→気血水の診断をして、体質に合った習慣を実践してみましょう！</p>
                <div class="p-4 text-gray-900">
                    <a href="{{ route('diagnoses.index') }}" class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('診断する') }}
                    </a>
                </div>
                <div class="p-4 text-gray-900">
                    <a href="{{ route('posts.index') }}" class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('体質に合った習慣をみる') }}
                    </a>
                    <p class="text-sm mt-4">※診断をしていない場合は診断画面に移動します。</p>
                </div>

        </div>
    </div>
</x-app-layout>
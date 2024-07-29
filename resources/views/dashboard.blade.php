<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("ログインしました。") }}
                </div>
            </div>
            
        <!--説明スライド-->
        <div class="responsive-iframe-container" style="position: relative; max-width: 680px; width: 100%; height: auto; padding-top: 56.2500%;
             padding-bottom: 0; box-shadow: 0 2px 8px 0 rgba(63,69,81,0.16); margin-top: 1.6em; margin-bottom: 0.9em; overflow: hidden;
             border-radius: 8px; will-change: transform;">
          <iframe loading="lazy" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0;margin: 0;"
            src="https://www.canva.com/design/DAGKKp9FRpM/iPqdHlk_h3CE9QxptWkuRg/view?embed" allowfullscreen="allowfullscreen" allow="fullscreen">
          </iframe>
        </div>
        <a href="https://www.canva.com/design/DAGKKp9FRpM/iPqdHlk_h3CE9QxptWkuRg/view?utm_content=DAGKKp9FRpM&amp;utm_campaign=designshare&amp;utm_medium=embeds&amp;utm_source=link" target="_blank" rel="noopener">アプリの使い方をご覧いただき、診断から開始してください。</a>
        
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('diagnoses.index') }}" class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('診断する') }}
                    </a>
                </div>
            <div class="p-6 text-gray-900">
                    <a href="{{ route('posts.index') }}" class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('体質に合った習慣をみる') }}
                    </a>
                    <p class="text-sm mt-4">※診断をしていない場合は診断画面に移動します。</p>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
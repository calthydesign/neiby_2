<?php
//postsテーブルのマイグレーションファイル

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id');              //外部キー
                $table->string('weather')->nullable();      //天気
                $table->string('condition');                //今日の調子
                $table->string('memo')->nullable();         //今日のメモ
	            $table->text('selected_tags')->nullable(); //選択したタグ
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstructionsTable extends Migration
{
    public function up()
    {
        Schema::create('constructions', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // 体質の名前
            $table->text('description');                  // 体質の説明
            $table->text('meal_recommendation');          // おすすめの食事
            $table->text('exercise_recommendation');      // おすすめの運動
            $table->text('lifestyle_recommendation');     // おすすめのライフスタイル
            $table->string('meal_vegetables')->nullable();
            $table->string('meal_fruits')->nullable();
            $table->string('meal_fish_meat')->nullable();
            $table->string('meal_seasonings')->nullable();
            $table->string('meal_dried_goods')->nullable();
            $table->string('meal_tea')->nullable();
            $table->string('exercise')->nullable();
            $table->string('lifestyle')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('constructions');
    }
}
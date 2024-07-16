<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    //診断結果のカウント登録
    public function up()
    {
        Schema::create('diagnoses', function (Blueprint $table) {
    	      $table->id();
    	      $table->unsignedBigInteger('user_id');
    	      $table->string('result');
    	      $table->integer('kekkyo_count')->default(0);
    	      $table->integer('kikyo_count')->default(0);
    	      $table->integer('kitai_count')->default(0);
    	      $table->integer('oketsu_count')->default(0);
    	      $table->integer('suitai_count')->default(0);
    	      $table->date('date')->default(now());
    	      $table->timestamps();
    	
    	      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    	  });
    }

    //
    public function down(): void
    {
        Schema::dropIfExists('diagnosesla');
    }
};

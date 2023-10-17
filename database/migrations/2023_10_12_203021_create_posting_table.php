<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postings', function (Blueprint $table) {
            $table->id();
            $table->string('project')->nullable();
            $table->string('site')->nullable();
            $table->string('element')->nullable();
            $table->string('title')->nullable();
            $table->longText('body');
            $table->integer('user_id')->unsigned()->index();
            $table->dateTime('onlineFrom')->nullable();
            $table->dateTime('onlineTo')->nullable();
            $table->boolean('active')->nullable()->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postings');
    }
};

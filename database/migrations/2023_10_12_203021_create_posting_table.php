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
            $table->string('project', 100)->nullable();
            $table->string('site', 100)->nullable();
            $table->string('element', 100)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->longText('body');
            $table->integer('user_id')->unsigned()->index();
            $table->dateTime('onlineFrom')->nullable();
            $table->dateTime('onlineTo')->nullable();
            $table->boolean('active')->nullable()->default(false);
            $table->string('language', 2)->default('en');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postings');
    }
};

<?php

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
        Schema::create('to_do_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('is_group', ['0', '1'])->default('0'); // 0 = individual
            $table->enum('is_complete', ['0', '1'])->default('0'); // 0 = progress
            $table->text('description')->nullable();
            $table->time('timer')->nullable();
            $table->integer('total_seconds')->nullable();
            $table->time('elapsed')->nullable();
            $table->enum('timer_started', ['0', '1'])->default('0')->nullable(); // 0 = stop
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('to_do_lists');
    }
};

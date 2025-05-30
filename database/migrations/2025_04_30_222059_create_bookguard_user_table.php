<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookguard_user', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedInteger('bookguard_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('class_id')->nullable();
            $table->timestamps();

            $table->unique(['bookguard_id', 'user_id'], 'bookguard_user_unique');

            $table->foreign('bookguard_id')
                ->references('id')
                ->on('bookguards')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookguard_user');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookguard_user', function (Blueprint $table) {
            $table->unsignedInteger('bookguard_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->primary(['bookguard_id', 'user_id']);

            $table->foreign('bookguard_id')
                ->references('id')
                ->on('bookguards')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookguard_user');
    }
};

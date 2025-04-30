<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bookguard_id']);
            $table->dropColumn('bookguard_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('bookguard_id')->nullable();
            $table->foreign('bookguard_id')
                    ->references('id')
                    ->on('bookguards')
                    ->onDelete('set null');
        });
    }
};


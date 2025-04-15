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
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('phone', 15);
            $table->char('dni', 9)->unique();
            $table->string('image_profile', 255)->default('default.png');
            $table->boolean('available')->default(true);
            $table->unsignedInteger('role_id')->nullable();
            $table->unsignedInteger('bookguard_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('set null');

            $table->foreign('bookguard_id')
                ->references('id')
                ->on('bookguards')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

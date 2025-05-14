<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('password', 255)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('phone', 15)->nullable();
            $table->char('dni', 9)->unique()->nullable();
            $table->string('google_id')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('image_profile', 255)->default('default.png');
            $table->boolean('available')->default(true);
            $table->boolean('enabled')->default(true);
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

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

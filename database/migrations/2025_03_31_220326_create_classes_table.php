<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedInteger('num_class');
            $table->char('course',5);
            $table->char('code', 1);
            $table->boolean('enabled')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->unique(['num_class', 'course', 'code'], 'class_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};

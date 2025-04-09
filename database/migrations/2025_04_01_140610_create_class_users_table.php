<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_users', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('num_class');
            $table->unsignedInteger('course');
            $table->char('code', 1)->nullable();

            $table->primary(['user_id', 'num_class', 'course', 'code']);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign(['num_class', 'course', 'code'])
                ->references(['num_class', 'course', 'code'])
                ->on('classes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_users');
    }
};

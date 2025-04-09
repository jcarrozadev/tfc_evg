<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guards', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->date('date');
            $table->string('text_guard', 150)->nullable();
            $table->time('hour');
            $table->unsignedInteger('user_sender_id');
            $table->unsignedInteger('absence_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('user_sender_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('absence_id')
                ->references('id')
                ->on('absences')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guards');
    }
};

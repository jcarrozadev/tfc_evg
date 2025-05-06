<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->date('date');
            $table->time('hour_start')->nullable();
            $table->time('hour_end')->nullable();
            $table->string('justify', 255)->nullable();
            $table->string('info_task', 255)->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('reason_id');
            $table->string('reason_description', 255)->nullable();
            $table->boolean('status')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('reason_id')
                ->references('id')
                ->on('reasons')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};

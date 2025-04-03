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
        Schema::create('absences', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->date('date');
            $table->time('hour_start');
            $table->time('hour_end');
            $table->string('justify', 255);
            $table->string('info_task', 255);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('reason_id');
            $table->unsignedInteger('status_id');
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

            $table->foreign('status_id')
                ->references('id')
                ->on('status')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};

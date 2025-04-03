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
        Schema::create('guards', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->date('date');
            $table->string('archive', 150)->nullable();
            $table->time('hour');
            $table->unsignedInteger('user_sender_id');
            $table->unsignedInteger('user_receiver_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('user_sender_id')
                ->references('id')
                ->on('users_evg')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_receiver_id')
                ->references('id')
                ->on('users_evg')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guards');
    }
};

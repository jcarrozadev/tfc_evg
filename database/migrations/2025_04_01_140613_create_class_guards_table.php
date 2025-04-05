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
        Schema::create('class_guards', function (Blueprint $table) {
            $table->unsignedInteger('num_class');
            $table->unsignedInteger('course_id');
            $table->char('code', 1);
            $table->unsignedInteger('guard_id');
            $table->date('date');
            $table->time('hour');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->primary(['num_class', 'course_id', 'code', 'guard_id']);

            $table->foreign(['num_class', 'course_id', 'code'])
                ->references(['num_class', 'course_id', 'code'])
                ->on('classes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('guard_id')
                ->references('id')
                ->on('guards')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_guards');
    }
};

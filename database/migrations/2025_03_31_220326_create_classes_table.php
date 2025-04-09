<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->unsignedInteger('num_class');
            $table->unsignedInteger('course');
            $table->char('code', 1)->nullable();
            $table->unsignedInteger('bookguard_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->primary(['num_class', 'course', 'code']);

            $table->foreign('bookguard_id')
                ->references('id')
                ->on('bookguards')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};

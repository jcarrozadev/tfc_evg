<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('absence_files', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary();
            $table->unsignedInteger('absence_id');
            $table->string('file_path');
            $table->string('original_name'); 
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('absence_id')
                ->references('id')
                ->on('absences')
                ->onDelete('cascade');
        });

        
    }

    public function down(): void
    {
        Schema::dropIfExists('absence_files');
    }
};


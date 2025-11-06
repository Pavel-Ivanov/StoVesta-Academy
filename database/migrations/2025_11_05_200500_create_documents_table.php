<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status')->default('draft');
            $table->index('status');
            // Тип документа (например: certificate, form, handbook)
            $table->string('type');
            $table->index('type');
            // Описание документа (необязательно)
            $table->text('description')->nullable();
            // Видимость документа (минимальная роль)
            $table->string('visibility')->default('all');
            $table->boolean('is_published')->default(false);
            $table->index('is_published');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};

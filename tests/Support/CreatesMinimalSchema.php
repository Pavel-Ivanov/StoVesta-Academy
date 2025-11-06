<?php

namespace Tests\Support;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

trait CreatesMinimalSchema
{
    protected function setUpMinimalSchema(): void
    {
        // Users table (minimal)
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->nullable()->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Roles table (Spatie)
        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('guard_name')->default('web');
                $table->timestamps();
            });
        }

        // Model has roles pivot (Spatie)
        if (! Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type']);
                $table->primary(['role_id', 'model_id', 'model_type']);
            });
        }

        // Documents table (pages-only files)
        if (! Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('type');
                $table->index('type');
                $table->string('status')->default('draft');
                $table->boolean('is_published')->default(false);
                $table->text('description')->nullable();
                $table->string('visibility')->default('all');
                $table->timestamps();
            });
        }

        // Document pages table for multi-sheet documents
        if (! Schema::hasTable('document_pages')) {
            Schema::create('document_pages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('document_id');
                $table->string('title')->nullable();
                $table->string('file_path');
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }
}

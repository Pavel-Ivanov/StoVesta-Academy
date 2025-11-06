<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'title', 'type', 'status', 'is_published', 'description', 'visibility',
    ];

    protected $casts = [
        'type' => DocumentType::class,
        'status' => DocumentStatus::class,
        'is_published' => 'boolean',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(DocumentPage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function getPrimaryFilePathAttribute(): ?string
    {
        $firstPage = $this->relationLoaded('pages') ? $this->pages->first() : $this->pages()->first();
        return $firstPage?->file_path;
    }

    public function getPrimaryFileUrlAttribute(): ?string
    {
        $path = $this->primary_file_path;
        return $path ? Storage::disk('public')->url($path) : null;
    }
}

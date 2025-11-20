<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentPage extends Model
{
    protected $fillable = [
        'document_id', 'title', 'file_path', 'sort_order',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}

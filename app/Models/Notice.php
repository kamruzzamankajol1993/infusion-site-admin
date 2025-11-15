<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'date',
        'pdf_file',
    ];

    /**
     * Get the category that owns the notice.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(NoticeCategory::class, 'category_id');
    }
}
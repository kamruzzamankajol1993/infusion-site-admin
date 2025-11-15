<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceKeypoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'keypoint',
    ];

    /**
     * Get the service that owns the keypoint.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class OfficerCategory extends Model
{
    use HasFactory;

    // Add 'order_column' to fillable
    protected $fillable = ['name', 'parent_id', 'status', 'order_column'];

    public function parent()
    {
        return $this->belongsTo(OfficerCategory::class, 'parent_id');
    }

    /**
     * Get the child categories, ordered by the new column.
     */
    public function children(): HasMany
    {
        // Add orderBy
        return $this->hasMany(OfficerCategory::class, 'parent_id')->orderBy('order_column', 'asc');
    }

    public function officers() {
        return $this->belongsToMany(Officer::class, 'officer_officer_category')
                    ->withPivot('order_column')
                    ->orderBy('pivot_order_column', 'asc');
    }

    // --- 3. ADD THE TWO NEW METHODS BELOW ---

    /**
     * Get all descendant categories (children, grandchildren, etc.)
     * Eager loads them recursively.
     */
    public function descendants(): HasMany
    {
        return $this->hasMany(OfficerCategory::class, 'parent_id')->with('descendants');
    }

    /**
     * Get all descendant category IDs as a flat array.
     */
    public function getAllDescendantIds(): array
    {
        $ids = [];
        // Note: We load 'descendants' relation in the controller
        foreach ($this->descendants as $child) { 
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }
        return $ids;
    }

    // --- END OF NEW METHODS ---
}
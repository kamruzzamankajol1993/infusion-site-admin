<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentInfo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'department_infos'; // Specify plural table name

    protected $fillable = [
        'officer_id',
        'designation_id',
        'department_id',
        'additional_text',
    ];

    /**
     * Get the officer that owns this info (Belongs-To).
     */
    public function officer()
    {
        return $this->belongsTo(Officer::class);
    }

    /**
     * Get the designation associated with this info.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class); // Assuming you have a Designation model
    }

    /**
     * Get the department associated with this info.
     */
    public function department()
    {
        return $this->belongsTo(Department::class); // Assuming you have a Department model
    }
}
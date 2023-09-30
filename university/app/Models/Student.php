<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'bachelor_final_mark',
        'master_final_mark',
        'phd_final_mark',
        'outside_prescribed_time',
        'user',
        'degree',
        'degree_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $casts = [
        'outside_prescribed_time' => 'boolean'
    ];

    public function degree () : BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The storage format of the model's date column.
     *   
     * @var string 
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'bachelor_final_mark',
        'master_final_mark',
        'phd_final_mark',
        'outside_prescribed_time',
        'user',
        'degree',
        'degree_id',
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
        'pivot',
    ];

    protected $casts = [
        'outside_prescribed_time' => 'boolean',
    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)
            ->withTimestamps()
            ->whereNull('course_student.deleted_at');
    }
}

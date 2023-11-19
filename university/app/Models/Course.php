<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The storage format of the model's date column.
     *   
     * @var string 
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = ['name', 'sector', 'starting_date', 'ending_date', 'cfu'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'professor_id', 'pivot'];

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)
            ->withTimestamps()
            ->whereNull('course_student.deleted_at');
    }
}

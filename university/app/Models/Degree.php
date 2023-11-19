<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Degree extends Model
{
    use HasFactory;

    /**
     * The storage format of the model's date column.
     *   
     * @var string 
     */
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        'name',
        'code',
        'course_type',
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

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}

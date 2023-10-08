<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'sector', 'starting_date', 'ending_date', 'cfu'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function professor () : BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
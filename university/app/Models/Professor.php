<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject', 'level'
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

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

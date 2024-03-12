<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exercise extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "gif_url",
        "type_id"
    ];
    public function type(): BelongsTo
    {
        return $this->belongsTo(ExerciseType::class, 'type_id');
    }
}

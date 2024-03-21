<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "qty",
        "type_id",
        'status',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(MealType::class, "type_id");
    }
}

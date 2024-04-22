<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Exercise extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "media",
        "coach_id",
        "type_id",
        "muscle",
        "equipment_id",
        "duration",
        "description",
        "status",
    ];
    public const muscles = [
        'abs',
        'quads',
        'glutes',
        'triceps',
        'biceps',
        'back',
        'chest',
        'leg',
        'calf',
        'wrist',
    ];

    public function media(): Attribute
    {
        return Attribute::make(
            get: function ($attr) {
                if (!empty($attr)) {
                    $host = request()->getSchemeAndHttpHost(); // http:127.0.01:8000
                    $newAttr = Str::replace("public", 'storage', $attr);
                    return "$host/$newAttr";
                }
            }
        );
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ExerciseType::class, 'type_id');
    }
    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(ExerciseEquipment::class, 'equipment_id');
    }
}

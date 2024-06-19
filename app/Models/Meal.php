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
        "qty_type_id",
        "coach_id",
        "ingredients",
        "description",
        'status',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(MealType::class, "type_id");
    }
    public function qty_type(): BelongsTo
    {
        return $this->belongsTo(QuantityType::class, 'qty_type_id');
    }
    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }
    public function format()
    {
        return (object)[
            "id" => $this->id,
            "name" => $this->name,
            "type" => $this->type->name,
            "qty_type" => $this->qty_type->title,
            "qty" => "" . $this->qty,
            "ingredients" => $this->ingredients,
            "description" => $this->description,
        ];
    }
}

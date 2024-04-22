<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExerciseEquipment extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'equipment_id');
    }
}

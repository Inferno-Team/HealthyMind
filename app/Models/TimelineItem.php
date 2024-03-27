<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TimelineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'timeline_id',
        'item_id',
        'item_type',
        'day_id',
    ];
    public function item(): MorphTo
    {
        return $this->morphTo();
    }
    public function day():BelongsTo{
        return $this->belongsTo(Day::class,'day_id');
    }
}

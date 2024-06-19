<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "timeline_item_id",
        "percentage",
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(NormalUser::class, 'user_id');
    }
    public function progress(): BelongsTo
    {
        return $this->belongsTo(TimelineItem::class, 'timeline_item_id');
    }
}

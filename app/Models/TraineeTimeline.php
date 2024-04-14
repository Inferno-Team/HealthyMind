<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainee_id',
        'timeline_id',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(NormalUser::class, 'trainee_id');
    }
    public function timeline(): BelongsTo
    {
        return $this->belongsTo(CoachTimeline::class, 'timeline_id');
    }
}

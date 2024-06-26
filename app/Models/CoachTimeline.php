<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoachTimeline extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "coach_id",
        "goal_plan_disease_id",
        "description",
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class, 'coach_id');
    }
    public function goal_plan_disease(): BelongsTo
    {
        return $this->belongsTo(GoalPlanDisease::class, 'goal_plan_disease_id');
    }
    public function timeline_trainees(): HasMany
    {
        return $this->hasMany(TraineeTimeline::class, 'timeline_id');
    }
    public function items(): HasMany
    {
        return $this->hasMany(TimelineItem::class, 'timeline_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoachTimeline extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "coach_id",
        "goal_plan_disease_id"
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    public function goal_plan_disease(): BelongsTo
    {
        return $this->belongsTo(GoalPlanDisease::class, 'goal_plan_disease_id');
    }
}

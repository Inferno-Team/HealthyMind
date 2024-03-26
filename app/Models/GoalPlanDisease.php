<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoalPlanDisease extends Model
{
    use HasFactory;
    protected $fillable = [
        "plan_id",
        "goal_id",
        "disease_id"
    ];
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }
    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class, 'goal_id');
    }
    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }
    public function timelines(): HasMany
    {
        return $this->hasMany(CoachTimeline::class, 'goal_plan_disease_id');
    }
}

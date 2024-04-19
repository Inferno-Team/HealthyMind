<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoalPlanDisease extends Model
{
    use HasFactory;
    private const separator = ",";
    protected $fillable = [
        "plan_id",
        "goal_ids",
        "disease_ids"
    ];
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function goals()
    {
        $ids = explode(self::separator, $this->goal_ids);
        if (empty($ids))
            return collect([]);
        return Goal::whereIn('id', $ids)->get();
    }
    public function goals_name()
    {
        $goals = $this->goals();
        $names = $goals->pluck('name')->toArray();
        $name = implode(' | ', $names);
        return $name;
    }
    public function disease()
    {
        $ids = explode(self::separator, $this->disease_ids);
        if (empty($ids))
            return collect([]);
        return Disease::whereIn('id', $ids)->get();
    }
    public function disease_name()
    {
        $disease = $this->disease();
        $names = $disease->pluck('name')->toArray();
        $name = implode(' | ', $names);
        return $name;
    }
    public function timelines(): HasMany
    {
        return $this->hasMany(CoachTimeline::class, 'goal_plan_disease_id');
    }
}

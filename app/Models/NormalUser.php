<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class NormalUser extends User
{
    use HasSlug;
    protected $appends = ['is_pro', 'fullname'];
    public function isPro(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) =>
            isset($this->user_premium_request)
                && $this->user_premium_request->status == 'approved',

        );
    }
    public function goalPlanDisease(): HasOneThrough
    {
        return $this->hasOneThrough(
            GoalPlanDisease::class,
            UserPlanGoalDisease::class,
            'user_id',      // Foreign key on the intermediate table (UserPlanGoalDisease)
            'id',           // Foreign key on the final related table (GoalPlanDisease)
            'id',           // Local key on the initial table (User)
            'goal_plan_disease_id'  // Local key on the intermediate table (UserPlanGoalDisease)
        );
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(TraineeTimeline::class, 'trainee_id');
    }

    public function user_premium_request(): HasOne
    {
        return $this->hasOne(UserPremiumRequest::class, 'user_id');
    }
    public function details(): HasOne
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function (User $user) {
            $user->forceFill(['type' => 'normal']);
        });
    }
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(["first_name", "last_name"])
            ->saveSlugsTo('username')
            ->slugsShouldBeNoLongerThan(25)
            ->usingSeparator('_')
            ->preventOverwrite();
    }
}

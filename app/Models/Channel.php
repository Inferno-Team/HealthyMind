<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Channel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
    ];
    public function subscriptions(): HasMany
    {
        return $this->hasMany(ChannelSubscription::class, 'channel_id');
    }
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            ChannelSubscription::class,
            'channel_id',
            'id',
            'id',
            'user_id'
        );
    }
}

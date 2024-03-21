<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChannelSubscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'channel_id',
        'user_id',
    ];
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function messages(): HasMany
    {
        return $this->hasMany(SubscriptionMessage::class, 'subscription_id');
    }
}

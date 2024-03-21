<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscription_id',
        'message'
    ];
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(ChannelSubscription::class, 'subscription_id');
    }
}

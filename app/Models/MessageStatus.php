<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'subscription_id',
        'status',
    ];
    public function message(): BelongsTo
    {
        return $this->belongsTo(SubscriptionMessage::class, 'message_id');
    }
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(ChannelSubscription::class);
    }
}

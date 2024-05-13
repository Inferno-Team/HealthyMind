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
        'member_id',
        'status',
    ];
    public function message(): BelongsTo
    {
        return $this->belongsTo(SubscriptionMessage::class, 'message_id');
    }
    public function member(): BelongsTo
    {
        return $this->belongsTo(ConversationMember::class, 'member_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'message'
    ];
    public function member(): BelongsTo
    {
        return $this->belongsTo(ConversationMember::class, 'member_id');
    }
    public function statuses(): HasMany
    {
        return $this->hasMany(MessageStatus::class, 'message_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ConversationMember extends Model
{
    use HasFactory;
    protected $fillable = [
        "conversation_id",
        "user_id",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function conversation(): BelongsTo
    {
        return  $this->belongsTo(Conversation::class, 'conversation_id');
    }
    public function messages(): HasMany
    {
        return $this->hasMany(SubscriptionMessage::class, 'member_id');
    }
}

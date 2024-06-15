<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "channel_id",
        "avatar",
    ];

    public function avatar(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attr) {
                if (empty($value) || empty($attr))
                    return null;
                $prefix = env('APP_URL');
                return "$prefix/storage/$value";
            }
        );
    }
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }
    public function members(): HasMany
    {
        return $this->hasMany(ConversationMember::class, 'conversation_id');
    }
    public function messages(): HasManyThrough
    {
        return $this->hasManyThrough(
            SubscriptionMessage::class,
            ConversationMember::class,
            null,
            "member_id",
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    use HasFactory;
    protected $appends = ['avatar'];
    protected $fillable = [
        "name",
        "channel_id",
        'type',
    ];
    public const ONE_ON_ONE_CONV = 'one-on-one';
    public const PUBLIC_CONV = 'public-conv';

    public function avatar(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attr) {
                if ($attr['type'] == self::ONE_ON_ONE_CONV) {
                    // get the other user avatar.
                    $other_member = $this->members()->whereNot('id', Auth::id())->first();
                    if (!empty($other_member->user->avatar)) {
                        return  $other_member->user->avatar;
                    }
                    return null;
                } else {
                    return asset('/img/team-1.jpg');
                }
                // if (empty($value) || empty($attr))
                //     return null;
                // $prefix = env('APP_URL');
                // return "$prefix/storage/$value";
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

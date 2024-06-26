<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $first_name
     * @property string $last_name
     * @property string|null $username
     * @property string $email
     * @property string|null $phone
     * @property string $password
     * @property string $type
     * @property string $status
     * @property-read string|null $avatar
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Channel> $channels
     * @property-read int|null $channels_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConversationMember> $conversationMembership
     * @property-read int|null $conversation_membership_count
     * @property-read mixed $fullname
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionMessage> $message
     * @property-read int|null $message_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChannelSubscription> $subscriptions
     * @property-read int|null $subscriptions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereAvatar($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereFirstName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLastName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUsername($value)
     */
    class Admin extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property string $type
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Conversation> $conversations
     * @property-read int|null $conversations_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionMessage> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChannelSubscription> $subscriptions
     * @property-read int|null $subscriptions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     * @method static \Database\Factories\ChannelFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|Channel newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Channel newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Channel query()
     * @method static \Illuminate\Database\Eloquent\Builder|Channel whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Channel whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Channel whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Channel whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Channel whereUpdatedAt($value)
     */
    class Channel extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $channel_id
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Channel $channel
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionMessage> $messages
     * @property-read int|null $messages_count
     * @property-read \App\Models\User $user
     * @method static \Database\Factories\ChannelSubscriptionFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|ChannelSubscription newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ChannelSubscription newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ChannelSubscription query()
     * @method static \Illuminate\Database\Eloquent\Builder|ChannelSubscription whereChannelId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChannelSubscription whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChannelSubscription whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChannelSubscription whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ChannelSubscription whereUserId($value)
     */
    class ChannelSubscription extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $first_name
     * @property string $last_name
     * @property string|null $username
     * @property string $email
     * @property string|null $phone
     * @property string $password
     * @property string $type
     * @property string $status
     * @property-read string|null $avatar
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Channel> $channels
     * @property-read int|null $channels_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConversationMember> $conversationMembership
     * @property-read int|null $conversation_membership_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exercise> $exercises
     * @property-read int|null $exercises_count
     * @property-read mixed $fullname
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Meal> $meals
     * @property-read int|null $meals_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionMessage> $message
     * @property-read int|null $message_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChannelSubscription> $subscriptions
     * @property-read int|null $subscriptions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TraineeTimeline> $timeline_trainees
     * @property-read int|null $timeline_trainees_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CoachTimeline> $timelines
     * @property-read int|null $timelines_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @method static \Illuminate\Database\Eloquent\Builder|Coach newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Coach newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Coach query()
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereAvatar($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereFirstName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereLastName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Coach whereUsername($value)
     */
    class Coach extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property int|null $coach_id
     * @property int $goal_plan_disease_id
     * @property string|null $description
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Coach|null $coach
     * @property-read \App\Models\GoalPlanDisease $goal_plan_disease
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TimelineItem> $items
     * @property-read int|null $items_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TraineeTimeline> $timeline_trainees
     * @property-read int|null $timeline_trainees_count
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline query()
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline whereCoachId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline whereGoalPlanDiseaseId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|CoachTimeline whereUpdatedAt($value)
     */
    class CoachTimeline extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property int $channel_id
     * @property string $type
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read mixed $avatar
     * @property-read \App\Models\Channel $channel
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConversationMember> $members
     * @property-read int|null $members_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionMessage> $messages
     * @property-read int|null $messages_count
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation query()
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereChannelId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Conversation whereUpdatedAt($value)
     */
    class Conversation extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $conversation_id
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Conversation $conversation
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionMessage> $messages
     * @property-read int|null $messages_count
     * @property-read \App\Models\User $user
     * @method static \Illuminate\Database\Eloquent\Builder|ConversationMember newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ConversationMember newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ConversationMember query()
     * @method static \Illuminate\Database\Eloquent\Builder|ConversationMember whereConversationId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ConversationMember whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ConversationMember whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ConversationMember whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ConversationMember whereUserId($value)
     */
    class ConversationMember extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|Disease newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Disease newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Disease query()
     * @method static \Illuminate\Database\Eloquent\Builder|Disease whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Disease whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Disease whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Disease whereUpdatedAt($value)
     */
    class Disease extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property-read string|null $media
     * @property int|null $coach_id
     * @property int|null $type_id
     * @property string $muscle
     * @property int|null $equipment_id
     * @property-read string|null $duration
     * @property string|null $description
     * @property string $status
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Coach|null $coach
     * @property-read \App\Models\ExerciseEquipment|null $equipment
     * @property-read \App\Models\ExerciseType|null $type
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise query()
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereCoachId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereDuration($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereEquipmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereMedia($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereMuscle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereTypeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Exercise whereUpdatedAt($value)
     */
    class Exercise extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Exercise> $exercises
     * @property-read int|null $exercises_count
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseEquipment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseEquipment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseEquipment query()
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseEquipment whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseEquipment whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseEquipment whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseEquipment whereUpdatedAt($value)
     */
    class ExerciseEquipment extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType query()
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ExerciseType whereUpdatedAt($value)
     */
    class ExerciseType extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|Goal newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Goal newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Goal query()
     * @method static \Illuminate\Database\Eloquent\Builder|Goal whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Goal whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Goal whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Goal whereUpdatedAt($value)
     */
    class Goal extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $plan_id
     * @property string|null $goal_ids
     * @property string|null $disease_ids
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Plan $plan
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CoachTimeline> $timelines
     * @property-read int|null $timelines_count
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease query()
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease whereDiseaseIds($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease whereGoalIds($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease wherePlanId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GoalPlanDisease whereUpdatedAt($value)
     */
    class GoalPlanDisease extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property int $type_id
     * @property int $qty
     * @property int|null $qty_type_id
     * @property int|null $coach_id
     * @property string|null $ingredients
     * @property string|null $description
     * @property string $status
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\Coach|null $coach
     * @property-read \App\Models\QuantityType|null $qty_type
     * @property-read \App\Models\MealType $type
     * @method static \Illuminate\Database\Eloquent\Builder|Meal newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Meal newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Meal query()
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereCoachId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereDescription($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereIngredients($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereQty($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereQtyTypeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereTypeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Meal whereUpdatedAt($value)
     */
    class Meal extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Meal> $meals
     * @property-read int|null $meals_count
     * @method static \Illuminate\Database\Eloquent\Builder|MealType newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MealType newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MealType query()
     * @method static \Illuminate\Database\Eloquent\Builder|MealType whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MealType whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MealType whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MealType whereUpdatedAt($value)
     */
    class MealType extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $message_id
     * @property int $member_id
     * @property string $status
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\ConversationMember $member
     * @property-read \App\Models\SubscriptionMessage $message
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus query()
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus whereMemberId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus whereMessageId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MessageStatus whereUpdatedAt($value)
     */
    class MessageStatus extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $first_name
     * @property string $last_name
     * @property string|null $username
     * @property string $email
     * @property string|null $phone
     * @property string $password
     * @property string $type
     * @property string $status
     * @property string|null $avatar
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Channel> $channels
     * @property-read int|null $channels_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConversationMember> $conversationMembership
     * @property-read int|null $conversation_membership_count
     * @property-read \App\Models\UserDetail|null $details
     * @property-read mixed $fullname
     * @property-read \App\Models\GoalPlanDisease|null $goalPlanDisease
     * @property-read mixed $is_pro
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionMessage> $message
     * @property-read int|null $message_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChannelSubscription> $subscriptions
     * @property-read int|null $subscriptions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserProgress> $timelinePorgesss
     * @property-read int|null $timeline_porgesss_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TraineeTimeline> $timelines
     * @property-read int|null $timelines_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \App\Models\UserPremiumRequest|null $user_premium_request
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser query()
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereAvatar($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereFirstName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereLastName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|NormalUser whereUsername($value)
     */
    class NormalUser extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $name
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @method static \Illuminate\Database\Eloquent\Builder|Plan newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Plan newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Plan query()
     * @method static \Illuminate\Database\Eloquent\Builder|Plan whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Plan whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Plan whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Plan whereUpdatedAt($value)
     */
    class Plan extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $en_title
     * @property string $ar_title
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Meal> $meals
     * @property-read int|null $meals_count
     * @property-read mixed $title
     * @method static \Database\Factories\QuantityTypeFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|QuantityType newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|QuantityType newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|QuantityType query()
     * @method static \Illuminate\Database\Eloquent\Builder|QuantityType whereArTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|QuantityType whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|QuantityType whereEnTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|QuantityType whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|QuantityType whereUpdatedAt($value)
     */
    class QuantityType extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $member_id
     * @property string $message
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\ConversationMember $member
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MessageStatus> $statuses
     * @property-read int|null $statuses_count
     * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionMessage newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionMessage newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionMessage query()
     * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionMessage whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionMessage whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionMessage whereMemberId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionMessage whereMessage($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubscriptionMessage whereUpdatedAt($value)
     */
    class SubscriptionMessage extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $timeline_id
     * @property string $item_type
     * @property int $item_id
     * @property string|null $event_date_start
     * @property string|null $event_date_end
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $item
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserProgress> $progress
     * @property-read int|null $progress_count
     * @property-read \App\Models\CoachTimeline $timeline
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem query()
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem whereEventDateEnd($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem whereEventDateStart($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem whereItemId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem whereItemType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem whereTimelineId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TimelineItem whereUpdatedAt($value)
     */
    class TimelineItem extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $trainee_id
     * @property int|null $timeline_id
     * @property int $enabled
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\CoachTimeline|null $timeline
     * @property-read \App\Models\NormalUser $trainee
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline query()
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline whereEnabled($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline whereTimelineId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline whereTraineeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|TraineeTimeline whereUpdatedAt($value)
     */
    class TraineeTimeline extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property string $first_name
     * @property string $last_name
     * @property string|null $username
     * @property string $email
     * @property string|null $phone
     * @property string $password
     * @property string $type
     * @property string $status
     * @property-read string|null $avatar
     * @property string|null $remember_token
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Channel> $channels
     * @property-read int|null $channels_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ConversationMember> $conversationMembership
     * @property-read int|null $conversation_membership_count
     * @property-read mixed $fullname
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubscriptionMessage> $message
     * @property-read int|null $message_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ChannelSubscription> $subscriptions
     * @property-read int|null $subscriptions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
     * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User query()
     * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
     */
    class User extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $user_id
     * @property float|null $weight
     * @property float|null $height
     * @property int|null $dob
     * @property string|null $gender
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User $user
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereDob($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereGender($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereHeight($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserDetail whereWeight($value)
     */
    class UserDetail extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $goal_plan_disease_id
     * @property int $user_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\GoalPlanDisease $goal_plan_disease
     * @property-read \App\Models\NormalUser $user
     * @method static \Illuminate\Database\Eloquent\Builder|UserPlanGoalDisease newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPlanGoalDisease newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPlanGoalDisease query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPlanGoalDisease whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPlanGoalDisease whereGoalPlanDiseaseId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPlanGoalDisease whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPlanGoalDisease whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPlanGoalDisease whereUserId($value)
     */
    class UserPlanGoalDisease extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $user_id
     * @property string|null $payment_process_code
     * @property string $status
     * @property string|null $others
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User $user
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest whereOthers($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest wherePaymentProcessCode($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserPremiumRequest whereUserId($value)
     */
    class UserPremiumRequest extends \Eloquent
    {
    }
}

namespace App\Models {
    /**
     *
     *
     * @property int $id
     * @property int $user_id
     * @property int $timeline_item_id
     * @property float $percentage
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\TimelineItem $progress
     * @property-read \App\Models\NormalUser $user
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress wherePercentage($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereTimelineItemId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserProgress whereUserId($value)
     */
    class UserProgress extends \Eloquent
    {
    }
}

<?php

namespace App\Http\Controllers\user;

use App\Events\core\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Helpers\FileHelper;
use App\Models\Channel;
use App\Models\ChannelSubscription;
use App\Models\Coach;
use App\Models\CoachTimeline;
use App\Models\Conversation;
use App\Models\ConversationMember;
use App\Models\Disease;
use App\Models\Goal;
use App\Models\GoalPlanDisease;
use App\Models\MessageStatus;
use App\Models\NormalUser;
use App\Models\Plan;
use App\Models\SubscriptionMessage;
use App\Models\TraineeTimeline;
use App\Models\User;
use App\Models\UserPremiumRequest;
use App\Notifications\coach\NewTraineeNotification;
use App\Notifications\NewMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function getGoalsDiseases()
    {
        $goals = Goal::all();
        $diseases = Disease::all();
        return $this->returnData("gd", [
            "goals" => $goals,
            "diseases" => $diseases,
        ]);
    }
    public function getGoalsDiseasesPlans(Request $request)
    {
        $goals = json_decode($request->goals);
        $diseases = json_decode($request->diseases);
        $goals_string = implode(",", $goals);
        $diseases_string = implode(",", $diseases);
        $goal_plan_diseases = GoalPlanDisease::where("goal_ids", $goals_string)
            ->where("disease_ids", $diseases_string)
            ->with('plan', 'timelines.coach')->get()->map(function (GoalPlanDisease $item) {
                $plan = $item->plan;
                $timelines = $item->timelines->map(function (CoachTimeline $timeline) {
                    return (object)[
                        "id" => $timeline->id,
                        "name" => $timeline->name,
                        "coach_name" => $timeline->coach->fullname,
                    ];
                });
                return [
                    "plan_name" => $plan->name,
                    "plan_id" => $plan->id,
                    "timelines" => $timelines,
                ];
            });
        return $this->returnData("plans", $goal_plan_diseases);
    }

    public function selectPlanTimelne(Request $request)
    {
        $coach_timeline = CoachTimeline::where('id', $request->input('id'))->get();
        if ($coach_timeline->isEmpty())
            return $this->returnMessage("timeline not found.");
        $coach_timeline = $coach_timeline->first();
        $user = NormalUser::where('id', Auth::id())->first();
        TraineeTimeline::create([
            'trainee_id' => $user->id,
            'timeline_id' => $coach_timeline->id,
        ]);
        $coach = Coach::where('id', $coach_timeline->coach_id)->first();

        $coach->notify(new NewTraineeNotification($user, $coach_timeline));
        return $this->returnMessage("plan saved.");
    }
    function myChannels(Request $request)
    {
        $channels = Auth::user()->channels->map(fn ($item) => (object)[
            "type" => $item->type,
            "name" =>  $item->name
        ]);
        return $this->returnData("channels", $channels);
    }
    function isPremium()
    {
        $user = NormalUser::where('id', Auth::id())->with('user_premium_request')->first();
        $user_premium_request = $user->user_premium_request;
        $status = $user_premium_request?->status ?? "";
        return $this->returnData("status", $status);
    }
    function me()
    {
        $user = NormalUser::find(Auth::id());
        $user = $user->format();
        unset($user->password);
        return $this->returnData("user", $user);
    }
    public function sendNewMessage(Request $request)
    {

        //we have channel id and user id => subscription id 
        $conversation = Conversation::where('id', $request->input('conversation'))
            ->whereHas('members', fn ($query) => $query->where('user_id', Auth::id()))->get();

        // check if this user is subscripted to this channel
        if ($conversation->isEmpty()) {
            return $this->returnError('you are not subuscripted to this channel.', 403);
        }
        $conversation = $conversation->first();
        $conversationMembership = $conversation->members->where('user_id', Auth::id())->first();
        $message = SubscriptionMessage::create([
            'member_id' => $conversationMembership->id,
            'message' => $request->input('message')
        ]);
        $currentUser = User::where('id', Auth::id())->first();
        // 1. send it as event to all subscribers
        event(new NewMessageEvent(
            $conversation->channel->name,
            $conversation->channel->type,
            $request->input('message'),
            $message->id,
            $request->input('conversation'),
            Carbon::parse($message->created_at)->format('h:i A'),
            Carbon::parse($message->created_at)->diffForHumans(),
            $currentUser
        ));
        // 2. save the status as received.
        // get all this channel subscription
        $subscriptions_id = ChannelSubscription::where('channel_id', $request->input('channel_id'))
            ->where('user_id', '!=', $currentUser->id)->get()->pluck('id');
        // create status for all channel-subscription and this message as status default value is received.
        foreach ($subscriptions_id as $id) {
            MessageStatus::create([
                'message_id' => $message->id,
                'subscription_id' => $id,
            ]);
        }
        return $this->returnMessage("message sent.");
    }

    public function loadConversationOldMessage($conversation_id)
    {

        if (!Conversation::where('id', $conversation_id)->exists()) {
            return $this->returnError("this chat are not avalible.", 404);
        }
        $user = User::where('id', auth::id())->first();
        $conversation = Conversation::where('id', $conversation_id)->first();
        info($conversation->members);
        if (!in_array($user->id, $conversation->members->pluck('user_id')->toArray())) {
            return $this->returnError("you are not subscribed to this chat.", 403);
        }

        // load messages from this channel.
        $items = SubscriptionMessage::whereHas('member', fn ($query) => $query->where('conversation_id', $conversation->id))
            ->with('statuses', 'member.user')->get()->sortBy('created_at')->map(function (SubscriptionMessage $item) use ($user) {
                // info($item);
                $statuses = $item->statuses()->whereHas('member', fn ($query) => $query->where('user_id', Auth::id()))
                    ->get();
                $myMessages = $user->message->pluck('id')->toArray();

                return (object)[
                    "message" => $item->message,
                    "message_id" => $item->id,
                    "created_at" => Carbon::parse($item->created_at)->isToday() ?
                        Carbon::parse($item->created_at)->format('h:i A')
                        : Carbon::parse($item->created_at)->format('Y/m/d h:i A'),
                    "status" => $statuses->isNotEmpty() ? $statuses->first()->status : null,
                    "full_name" => $item->member->user->fullname,
                    "avatar" => $item->member->user->avatar,
                    "status_id" =>  $statuses->isNotEmpty() ? $statuses->first()->id : null,
                    "is_me" => in_array($item->id, $myMessages),
                ];
            })->values();
        return $this->returnData("messages", $items);
    }
    public function allNotifications(Request $request)
    {
        $user = User::where('id', auth::id())->first();
        $notifications = $user->unreadNotifications->map(function ($notification) {
            if ($notification->type == NewMessage::class) {
                $data = $notification->data;
                return (object)[
                    "id" => $notification->id,
                    "title" => $data['conversation']['name'],
                    "body" => $data['sender']['fullname'] . " : " . $data['message']['message'],
                    "timestamp" => Carbon::parse($data['message']['created_at'])->getTimestampMs(),
                    "avatar" => Conversation::where('id', $data['conversation']['id'])->first()->avatar,
                ];
            }
            return $notification;
        });
        return $this->returnData("notifications", $notifications);
    }
    public function sendNotificationSeen(Request $request)
    {
        $user = User::find(Auth::id());
        $notification = $user->unreadNotifications()->where('id', $request->id)
            ->first();
        $notification?->markAsRead();
        return $this->returnMessage("read successfully.");
    }
    public function unreadNotifications(Request $request)
    {
        $user = User::where('id', auth::id())->first();
        $notifications = $user->unreadNotifications->map(function ($notification) {
            if ($notification->type == NewMessage::class) {
                $data = $notification->data;
                return (object)[
                    "id" => $notification->id,
                    "title" => $data['conversation']['name'],
                    "body" => $data['sender']['fullname'] . " : " . $data['message']['message'],
                    "timestamp" => Carbon::parse($data['message']['created_at'])->unix(),
                ];
            }
            return $notification;
        });
        return $this->returnData("notifications", $notifications);
    }

    public function getProfile(Request $request)
    {
        $user = NormalUser::find(Auth::id());
        return $this->returnData("profile", (object)[
            'fullname' => $user->fullname,
            'email' => $user->email,
            'weight' => $user->details->weight . "",
            'height' => $user->details->height . "",
            'dob' => Carbon::createFromTimestampMs($user->details->dob)->format("D-M-Y") . "",
            'coach' => $user->timelines()->first()->timeline->coach->fullname,
            'username' => $user->username,
            'firstname' => $user->first_name,
            'lastname' => $user->last_name,
            'timeline' => $user->timelines()->first()->timeline->name,
            'event_count' => $user->timelines()->first()->timeline->items->count(),
            'avatar' => $user->avatar,
        ]);
    }
    public function updateProfileAvatar(Request $request)
    {
        if ($request->hasFile("files")) {
            $files = $request->file('files');
            $avatar = $files[0];
            $user = NormalUser::where('id', Auth::id())->first();

            $file = FileHelper::uploadToDocs($avatar, "public/avatars/$user->username");
            $user->avatar = Str::replace("public/", "", $file);
            $user->update();
        }
        $user = $user->format();
        unset($user->password);
        return $this->returnData(
            "user",
            $user,
        );
    }
    public function sendPremiumRequest(Request $request)
    {
        $user = NormalUser::where('id', Auth::id())->with('user_premium_request')->first();
        if (!empty($user->user_premium_request) || isset($user->user_premium_request)) {
            return $this->returnError(
                "you already requsted premium,",
                403,
                ["status" => $user->user_premium_request->status]
            );
        }
        $premium_requset = UserPremiumRequest::create([
            'user_id' => $user->id,
            'payment_process_code' => $request->input('code'),
            'others' => json_encode(["provider" => 'syriatel_cash', 'via' => 'mobile_flutter']),
        ]);
        return $this->returnData('response', $premium_requset, 'request stored,waiting on admin approval.');
    }
    public function getCoachConversationWithMe()
    {
        $user = NormalUser::where('id', Auth::id())->first();
        if (!$user->isPro)
            return $this->returnError("you dont have access to this featcher", 403);
        $channel = $user->timelines()->first()->timeline->coach->privateChannel();
        // check if this user has conversation on this channel 
        $conversations = Conversation::where('channel_id', $channel->id)
            ->whereHas('members', fn ($query) => $query->where('user_id', $user->id))->get();
        info($conversations);
        info($conversations->isEmpty());

        if ($conversations->isEmpty()) {
            // create new conversation.
            $conversation = Conversation::create([
                "name" => $user->fullname,
                "channel_id" => $channel->id,
                "avatar" => $user->getRawOriginal('avatar'),
            ]);
            // create memebership for this user and for the coach on this conversaiton.
            $user_member = ConversationMember::create([
                "conversation_id" => $conversation->id,
                "user_id" => $user->id,
            ]);
            $caoch_member = ConversationMember::create([
                "conversation_id" => $conversation->id,
                "user_id" => $user->timelines()->first()->timeline->coach->id,
            ]);
            
        } else
            $conversation = $conversations->first();
        $channel_subscription = ChannelSubscription::where('user_id', $user->id)
            ->where('channel_id', $channel->id)->firstOr(function () use ($user, $channel) {
                return ChannelSubscription::create([
                    'channel_id' => $channel->id,
                    'user_id' => $user->id,
                ]);
            });
        info($conversation);
        return $this->returnData("conversation", (object)[
            "id" => $conversation->id,
            "name" => $conversation->name,
            "avatar" => $conversation->avatar,
            "channel_id" => $channel->id,
            "channel_name" => $channel->name,
            "channel_type" => $channel->type,
        ]);
    }
}

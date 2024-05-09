<?php

namespace App\Http\Controllers\user;

use App\Events\core\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelSubscription;
use App\Models\Conversation;
use App\Models\MessageStatus;
use App\Models\NormalUser;
use App\Models\SubscriptionMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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
        $status = $user_premium_request?->status ?? "pending";
        return $this->returnData("status", $status);
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
        $user = Auth::user();
        $conversation = Conversation::where('id', $conversation_id)->first();
        if (!in_array($user->id, $conversation->members->pluck('user_id')->toArray())) {
            return $this->returnError("you are not subscribed to this chat.", 403);
        }

        // load messages from this channel.
        $items = SubscriptionMessage::whereHas('member', fn ($query) => $query->where('conversation_id', $conversation->id))
            ->with('statuses', 'member.user')->get()->sortBy('created_at')->map(function (SubscriptionMessage $item) {
                // info($item);
                $statuses = $item->statuses()->whereHas('subscription', fn ($query) => $query->where('user_id', Auth::id()))
                    ->get();
                $myMessages = Auth::user()->message->pluck('id')->toArray();

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
}

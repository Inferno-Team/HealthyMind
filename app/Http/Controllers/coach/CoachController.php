<?php

namespace App\Http\Controllers\coach;

use App\Events\core\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelSubscription;
use App\Models\MessageStatus;
use App\Models\NormalUser;
use App\Models\SubscriptionMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachController extends Controller
{
    public function home_view(): View
    {
        // get all this user channels
        $channels = Auth::user()->channels->map(fn ($item) => (object)[$item->type => $item->name]);
        return view('pages.coach.dashboard', compact('channels'));
    }
    public function coach_profile(): View
    {
        return view('pages.coach.profile');
    }
    public function trainnes_view(): View
    {
        $coach = Auth::user();
        $trainees = NormalUser::whereHas('goalPlanDisease.timelines', function ($query) {
            $query->where('coach_id', Auth::id());
        })->get();
        return view('pages.coach.trainees', compact('trainees'));
    }
    public function chat_view(): View
    {
        $channels = Auth::user()->channels->map(fn ($item) => (object)[$item->type => $item->name]);
        $mySubscriptions = Auth::user()->subscriptions->pluck('id')->toArray();
        // chat has 4 things [sender avatar,sender full name, last message , last message timestamps,status]
        $chats = Auth::user()->messages()->with('message.subscription.user')->get()->sortByDesc('message.created_at')->groupBy('message.subscription.channel_id')
            ->map(function (Collection $message) use ($mySubscriptions) {

                $last_message = $message->last();
                // get last message sent via this channel
                $channel = Channel::where('id', $last_message->subscription->channel_id)->first();
                $last_message_on_channel = $channel->messages()->latest()->first();
                $first_channel_message = $channel->messages()->orderBy('created_at')->first();
                return (object)[
                    "avatar" => $first_channel_message->subscription->user->avatar,
                    "full_name" => $first_channel_message->subscription->user->fullname,
                    "last_msg" => $last_message_on_channel->message,
                    "status" => $last_message->status,
                    "timestamp" => $last_message_on_channel->created_at->diffForHumans(),
                    "timestamp_int" => Carbon::parse($last_message_on_channel->created_at)->unix(),
                    "id" => $last_message->subscription->channel_id,
                    "channel_name" => $last_message->subscription->channel->name,
                    "my_message" => in_array($last_message_on_channel->subscription_id, $mySubscriptions),
                ];
            })->values();
        return view('pages.coach.chat', compact('channels', 'chats'));
    }
    public function loadChat(Request $request)
    {
        $channelId = $request->input('channel');
        if (!Channel::where('id', $channelId)->exists()) {
            return $this->returnError("this chat are not avalible.", 404);
        }
        // check if this user is subscribed to this channel.
        $user = Auth::user();
        if ($user->subscriptions()->where('channel_id', $channelId)->get()->isEmpty()) {
            return $this->returnError("you are not subscribed to this chat.", 403);
        }
        // load messages from this channel.
        $items = SubscriptionMessage::whereHas('subscription', fn ($query) => $query->where('channel_id', $channelId))
            ->with('statuses', 'subscription.user')->get()->sortBy('created_at')->map(function (SubscriptionMessage $item) {
                // info($item);
                $statuses = $item->statuses()->whereHas('subscription', fn ($query) => $query->where('user_id', Auth::id()))
                    ->get();
                return (object)[
                    "message" => $item->message,
                    "message_id" => $item->id,
                    "created_at" => Carbon::parse($item->created_at)->isToday() ?
                        Carbon::parse($item->created_at)->format('h:i A')
                        : Carbon::parse($item->created_at)->format('Y/m/d h:i A'),
                    "status" => $statuses->isNotEmpty() ? $statuses->first()->status : null,
                    "full_name" => $item->subscription->user->fullname,
                    "avatar" => $item->subscription->user->avatar,
                    "is_me" => $item->subscription()->where('user_id', Auth::id())->get()->isNotEmpty(),
                    "status_id" =>  $statuses->isNotEmpty() ? $statuses->first()->id : null,
                ];
            })->values();
        return $this->returnData('chat', $items);
    }
    public function readMessage(Request $request)
    {
        $message_status_id = $request->input('message_status_id');
        $ms = MessageStatus::where('id', $message_status_id)->with('subscription')->get();
        if ($ms->isEmpty()) {
            return $this->returnError("Message Status Not Found.", 404);
        }
        $ms = $ms->first();
        if ($ms->subscription->user_id != Auth::id()) {
            return $this->returnError('This MS is not yours.', 403);
        }
        $ms->update(['status' => 'read']);
        return $this->returnMessage("MS Updated.");
    }
    public function readNewMessage(Request $request)
    {
        $status = MessageStatus::where('message_id', $request->input('message_id'))
            ->whereHas('subscription', fn ($query) => $query->where('user_id', Auth::id()))
            ->first();
        $status->update(['status' => 'read']);
        return $this->returnMessage("MS Updated.");
    }
    public function newMessage(Request $request)
    {

        //we have channel id and user id => subscription id 
        $subscription = ChannelSubscription::where('channel_id', $request->input('channel_id'))
            ->where('user_id', Auth::id())->with('channel')->get();
        // check if this user is subscripted to this channel
        if ($subscription->isEmpty()) {
            return $this->returnError('you are not subuscripted to this channel.', 403);
        }
        $subscription = $subscription->first();
        $message = SubscriptionMessage::create([
            'subscription_id' => $subscription->id,
            'message' => $request->input('message')
        ]);
        $currentUser = User::where('id', Auth::id())->first();
        // 1. send it as event to all subscribers
        event(new NewMessageEvent(
            $subscription->channel->name,
            $request->input('message'),
            $message->id,
            $request->input('channel_id'),
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
    }
}

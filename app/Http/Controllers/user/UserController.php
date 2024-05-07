<?php

namespace App\Http\Controllers\user;

use App\Events\core\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\ChannelSubscription;
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
            $subscription->channel->type,
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
        return $this->returnMessage("message sent.");
    }
}

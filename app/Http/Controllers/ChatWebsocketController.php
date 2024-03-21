<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChannelSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatWebsocketController extends Controller
{
    public function authenticateUser(Request $request)
    {
        $channelName = $request->input('channelName') ?? $request->input('channel_name');
        $channelName = str_replace('private-', '', $channelName);
        info($channelName);
        // check channel if exists in database.
        $channel = Channel::where('name', $channelName)->get();
        if ($channel->isEmpty())
            return $this->returnError("channel not found.", 404);
        $channel = $channel->first();
        $socketId = $request->input('socketId') ??  $request->input('socket_id');
        // Check if this user has subscription on this channel
        $user = Auth::user();
        $channelSubsctiption = ChannelSubscription::where('channel_id', $channel->id)
            ->where('user_id', $user->id)->get();
        if ($channelSubsctiption->isNotEmpty()) {
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'));
            // check channel type [private,presence].
            if ($channel->type == 'presence') {
                $auth = $pusher->authorizePresenceChannel($channelName, $socketId, $user->id);
                return $auth;
            } else {
                if ($channel->type == 'private') {
                    $auth = $pusher->authorizeChannel($channel->type.'-'.$channelName, $socketId, $user->id);
                    return $auth;
                }
                info("$user->username try to connect to channel : $channel->name as public channel.");
                return $this->returnError("can't connect to this channel", 403);
            }
        }

        return response()->json(['error' => 'Authentication failed'], 401);
    }
}

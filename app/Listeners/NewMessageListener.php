<?php

namespace App\Listeners;

use App\Events\core\NewMessageEvent;
use App\Models\ChannelSubscription;
use App\Models\Conversation;
use App\Models\ConversationMember;
use App\Models\SubscriptionMessage;
use App\Models\User;
use App\Notifications\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NewMessageListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewMessageEvent $event): void
    {
        // send new message notification for the other member of the same conversation.
        $message = SubscriptionMessage::where('id', $event->message_id)->with('member')->first();
        $conversation = Conversation::where('id', $message->member->conversation_id)->with('members')->first();
        // first check the channel type , if it private 
        // checkout that all subscribers are members of this conversation. 
        if ($event->channelType == 'private') {
            $subscribers = ChannelSubscription::where('channel_id', $event->channel_id)->get();
            $members = $conversation->members;
            foreach ($subscribers as $subscriber) {
                // it exists.
                $exists = $members->where('user_id', $subscriber->user_id)->isNotEmpty();
                if (!$exists) {
                    // create it.
                    ConversationMember::create([
                        "conversation_id" => $conversation->id,
                        "user_id" => $subscriber->user_id,
                    ]);
                }
            }
            // retrving the conversation again , so all the members will be avaliable.
            $conversation = Conversation::where('id', $message->member->conversation_id)->with('members')->first();
        }
        $members_ids = $conversation->members->pluck('user_id');
        $users = User::whereIn('id', $members_ids)->get();
        foreach ($users as $user) {
            if ($event->sender->id != $user->id) // don't send notification for sender.
                $user->notify(new NewMessage(
                    $message,
                    $conversation,
                    $event->sender,
                    $user,
                ));
        }
    }
}

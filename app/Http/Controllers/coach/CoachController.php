<?php

namespace App\Http\Controllers\coach;

use App\Events\core\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\coach\CreateNewItemRequest;
use App\Http\Requests\coach\CreateNewTimelineRequest;
use App\Models\Channel;
use App\Models\ChannelSubscription;
use App\Models\CoachTimeline;
use App\Models\Day;
use App\Models\Disease;
use App\Models\Exercise;
use App\Models\ExerciseType;
use App\Models\Goal;
use App\Models\GoalPlanDisease;
use App\Models\Meal;
use App\Models\MealType;
use App\Models\MessageStatus;
use App\Models\NormalUser;
use App\Models\Plan;
use App\Models\SubscriptionMessage;
use App\Models\TimelineItem;
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
    public function timelines_view(): View
    {
        $timelines = CoachTimeline::where('coach_id', Auth::id())->get();
        return view('pages.coach.timelines', compact('timelines'));
    }
    public function new_timeline_view(): View
    {
        $goals = Goal::all();
        $plans = Plan::all();
        $diseases = Disease::all();
        return view('pages.coach.create-timeline', compact('goals', 'plans', 'diseases'));
    }
    public function show_timeline_view(int $id): View
    {
        $timeline_id = $id;
        $items = TimelineItem::where('timeline_id', $id)->with('item.type')->orderBy('event_date_start')->get();
        $exercises = Exercise::with('type')->get();
        $meals = Meal::with('type')->get();
        return view('pages.coach.show-cal-timeline', compact('items', 'timeline_id', 'meals', 'exercises'));
    }
    public function show_timeline_add_item_view(int $timeline_id): View
    {
        $meals = Meal::all();
        $meal_types = MealType::all();
        $exercises = Exercise::all();
        $exercise_types = ExerciseType::all();
        $days = Day::all();

        return view('pages.coach.add-timeline-item', compact('exercises', 'meals', 'days', 'timeline_id', 'meal_types', 'exercise_types'));
    }

    public function show_all_meals(): View
    {
        $meals = Meal::with('type')->get();
        return view('pages.coach.all_meals', compact('meals'));
    }
    public function add_new_meal(): View
    {
        $meals = Meal::with('type')->get();
        return view('pages.coach.all_meals', compact('meals'));
    }
    public function show_all_exercises(): View
    {
        $exercises = Exercise::with('type')->get();
        return view('pages.coach.all_exercises', compact('exercises'));
    }
    public function new_timeline_store(CreateNewTimelineRequest $request)
    {
        $gps = GoalPlanDisease::where('plan_id', $request->plan)
            ->where('goal_id', $request->goal)
            ->where('disease_id', $request->disease)->firstOrCreate()->id;
        $timeline = CoachTimeline::create([
            'name' => $request->name,
            'goal_plan_disease_id' => $gps,
            'coach_id' => Auth::id(),
        ]);
        return $this->returnData('timeline', $timeline, 'Timeline created successfully.');
    }
    public function timeline_item_store(Request $request)
    {
        $timelineItem = new TimelineItem;
        if ($request->item_type == 'meal') {
            $item = Meal::find($request->item_id);
        } else if ($request->item_type == 'exercise') {
            $item = Exercise::find($request->item_id);
        }
        $timelineItem->timeline_id = $request->timeline_id;
        $timelineItem->event_date_start = Carbon::createFromTimestamp($request->dates['start'] / 1000);
        $timelineItem->event_date_end = Carbon::createFromTimestamp($request->dates['end'] / 1000);
        $timelineItem->item()->associate($item);
        $timelineItem->save();
        $timelineItem = TimelineItem::where('id', $timelineItem->id)->with('item.type')->first();
        return $this->returnData("item", $timelineItem, "Timeline Item Created.");
    }

    public function timeline_item_update(Request $request)
    {
        $method = $request->input('method');
        $timeline_item = TimelineItem::where('id', $request->id)->first();
        if ($method === 'drop') {
            [$event_date_start, $event_date_end] = $timeline_item->findDiff($request->delta);
        } else if ($method === 'resize') {
            info($request->input('event_date_start'));
            info(Carbon::parse($request->input('event_date_start')));
            info(Carbon::parse($request->input('event_date_end'), config('app.timezone')));
            info(Carbon::parse($request->input('event_date_end'), config('app.timezone'))->format('Y-m-d H:i:s'));
            [$event_date_start, $event_date_end]
                = [
                    Carbon::parse($request->input('event_date_start'), config('app.timezone'))->format('Y-m-d H:i:s'),
                    Carbon::parse($request->input('event_date_end'), config('app.timezone'))->format('Y-m-d H:i:s')
                ];
        }

        $timeline_item->event_date_start = $event_date_start;
        $timeline_item->event_date_end = $event_date_end;
        $timeline_item->update();
        return $this->returnMessage('Done.');
    }
    public function timeline_item_delete(Request $request)
    {
        TimelineItem::find($request->input('id'))->delete();
        return $this->returnMessage('Deleted.');
    }
    public function new_type_item(CreateNewItemRequest $request)
    {
        if ($request->type == 'meal') {
            $meal = Meal::create([
                "name" => $request->item_name,
                "qty" => $request->qty,
                "type_id" =>  $request->item_type,
                'status',
            ]);
            return $this->returnMessage("Meal Added");
        } else if ($request->type == 'exercise') {
            $exercise = Exercise::create([
                // "gif_url",
                "name" => $request->item_name,
                "qty" => $request->qty,
                "type_id" =>  $request->item_type,
            ]);
            return $this->returnMessage("Exercise Added");
        } {
        }
        return $this->returnMessage("no meal", 403);
    }
    public function timeline_delete(Request $request)
    {
        $timeline = CoachTimeline::where('id', $request->id)->get();
        if ($timeline->isEmpty()) {
            return $this->returnError("This timeline does not exists.", 404);
        }
        $timeline = $timeline->first();
        if ($timeline->coach_id != Auth::id()) {
            return $this->returnError("This timeline does't belongs to you.", 403);
        }
        CoachTimeline::where('id', $timeline->id)->delete();
        return $this->returnMessage("This timeline deleted successfully.");
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

<?php

namespace App\Http\Controllers\coach;

use App\Events\core\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Helpers\FileHelper;
use App\Http\Helpers\NotificationHelper;
use App\Http\Requests\coach\CreateNewItemRequest;
use App\Http\Requests\coach\CreateNewTimelineRequest;
use App\Models\Channel;
use App\Models\ChannelSubscription;
use App\Models\Coach;
use App\Models\CoachTimeline;
use App\Models\Conversation;
use App\Models\ConversationMember;
use App\Models\Day;
use App\Models\Disease;
use App\Models\Exercise;
use App\Models\ExerciseEquipment;
use App\Models\ExerciseType;
use App\Models\Goal;
use App\Models\GoalPlanDisease;
use App\Models\Meal;
use App\Models\MealType;
use App\Models\MessageStatus;
use App\Models\NormalUser;
use App\Models\Plan;
use App\Models\QuantityType;
use App\Models\SubscriptionMessage;
use App\Models\TimelineItem;
use App\Models\User;
use App\Notifications\admin\NewExerciseRequestNotification;
use App\Notifications\admin\NewMealRequestNotification;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoachController extends Controller
{
    public function home_view(): View
    {
        // get all this user channels
        $channels = Auth::user()->channels->map(fn ($item) => (object)[$item->type => $item->name]);
        $allCoachEvents = TimelineItem::whereHas('timeline', fn ($query) => $query->where('coach_id', Auth::id()))
            ->get();
        $coachEvents = $allCoachEvents->count();
        $lastWeekEvents = $allCoachEvents->where('created_at', '>=', Carbon::now()->subWeek())->count();
        $beforeLastWeekEvents = $allCoachEvents->where('created_at', '>=', Carbon::now()->subWeeks(2))
            ->where('created_at', '<', Carbon::now()->subWeeks(1))
            ->count();
        if ($beforeLastWeekEvents > $lastWeekEvents) {
            $diffBetweenWeeksEvents = $beforeLastWeekEvents - $lastWeekEvents;
            $per = round(100 * $diffBetweenWeeksEvents / $beforeLastWeekEvents);
            $differenceEventPercentage = -1 * $per;
        } else if ($lastWeekEvents > 0) {
            $diffBetweenWeeksEvents =  $lastWeekEvents - $beforeLastWeekEvents;
            $differenceEventPercentage = round(100 * $diffBetweenWeeksEvents / $lastWeekEvents);
        } else $differenceEventPercentage = 0;
        $trainees = NormalUser::whereHas('timelines.timeline', fn ($query) => $query->where('coach_id', Auth::id()))->with('timelines.timeline')->get();
        $lastWeekTrainees = $trainees->filter(fn ($item) => $item->timelines->where('created_at', '>=', Carbon::now()->subWeek())->count())->count();
        $beforeWeekTrainees = $trainees->filter(fn ($item) =>
        $item->timelines->where('created_at', '<', Carbon::now()->subWeek())
            ->where('created_at', '>=', Carbon::now()->subWeeks(2))
            ->count())->count();
        if ($beforeWeekTrainees > $lastWeekTrainees) {
            $diffBetweenWeeksTrainees = $beforeWeekTrainees - $lastWeekTrainees;
            $per = round(100 * $diffBetweenWeeksTrainees / $beforeWeekTrainees);
            $differenceTraineesPercentage = -1 * $per;
        } else if ($lastWeekTrainees > 0) {
            $diffBetweenWeeksTrainees =  $lastWeekTrainees - $beforeWeekTrainees;
            $differenceTraineesPercentage = round(100 * $diffBetweenWeeksTrainees / $lastWeekTrainees);
        } else $differenceTraineesPercentage = 0;
        $exercisesCount = Exercise::count();
        $myMeals = Coach::where('id', Auth::id())->with('meals')->first()->meals;
        $lastWeekMeals = $myMeals->where('created_at', '>=', Carbon::now()->subWeek())->count();

        $beforeLastWeekMeals = $myMeals
            ->where('created_at', '>=', Carbon::now()->subWeeks(2))
            ->where('created_at', '<', Carbon::now()->subWeeks(1))
            ->count();
        if ($beforeLastWeekMeals > $lastWeekMeals) {
            $diffBetweenWeeksMeals = $beforeLastWeekMeals - $lastWeekMeals;
            $per = round(100 * $diffBetweenWeeksMeals / $beforeLastWeekMeals);
            $differenceMealsPercentage = -1 * $per;
        } else if ($lastWeekMeals > 0) {
            $diffBetweenWeeksMeals =  $lastWeekMeals - $beforeLastWeekMeals;
            $differenceMealsPercentage = round(100 * $diffBetweenWeeksMeals / $lastWeekMeals);
        } else $differenceMealsPercentage = 0;
        $myMealThisYear = $myMeals->filter(function (Meal $meal) {
            $nowYear = Carbon::now()->year;
            $mealYear = Carbon::parse($meal->created_at)->year;
            return $nowYear == $mealYear;
        });
        $mealsTimeline = $myMealThisYear->groupBy(fn ($item) => $item->created_at->format('M'));
        $allCoachEventsTimeline = $allCoachEvents->groupBy(fn ($item) => $item->created_at->format('M'));
        $traineesTimeline = $trainees->filter(
            function ($item) {
                return $item->timelines->contains(function ($timeline) {
                    return $timeline->timeline->coach_id === Auth::id();
                });
            }
        )->map(function ($trainee) {
            return $trainee->timelines->where('timeline.coach_id', Auth::id())->first();
        })->groupBy(fn ($item) => $item->created_at->format('M'));
        $mealsTimelineValues = [
            "Jan" => 0, "Feb" => 0, "Mar" => 0, "Apr" => 0,
            "May" => 0, "Jun" => 0, "Jul" => 0, "Aug" => 0,
            "Sep" => 0, "Oct" => 0, "Nov" => 0, "Dec" => 0
        ];
        $eventsTimelineValues = array_merge([], $mealsTimelineValues);
        $traineesTimelineValues = array_merge([], $mealsTimelineValues);
        foreach ($mealsTimeline as $month => $value) {
            $mealsTimelineValues[$month] = $value->count();
        }
        foreach ($allCoachEventsTimeline as $month => $value) {
            $eventsTimelineValues[$month] = $value->count();
        }
        foreach ($traineesTimeline as $month => $value) {
            $traineesTimelineValues[$month] = $value->count();
        }

        return view('pages.coach.dashboard', compact(
            'channels',
            'differenceEventPercentage',
            'differenceTraineesPercentage',
            'differenceMealsPercentage',
            'exercisesCount',
            'mealsTimelineValues',
            'eventsTimelineValues',
            'traineesTimelineValues',
        ));
    }
    public function coach_profile(): View
    {
        return view('pages.coach.profile');
    }
    public function trainnes_view(): View
    {
        $coach = Auth::user();
        $trainees = NormalUser::whereHas('timelines.timeline', function ($query) {
            $query->where('coach_id', Auth::id());
        })->with('timelines.timeline')->get()->map(function ($trainee) {
            $timeline = $trainee->timelines->filter(fn ($item) =>
            $item->where('timeline', Auth::id()))
                ->values()->first()->timeline;
            return (object)[
                "id" => $trainee->id,
                "avatar" => $trainee->avatar,
                "first_name" => $trainee->first_name,
                "last_name" => $trainee->last_name,
                "username" => $trainee->username,
                "email" => $trainee->email,
                "isPro" => $trainee->isPro,
                "timeline" => $timeline,
                "created_at" => $trainee->created_at->diffForHumans(),

            ];
            // return $trainee;
        });
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
        $items = TimelineItem::where('timeline_id', $id)->has('item')->with('item.type')->orderBy('event_date_start')->get();
        $exercises = Exercise::with('type')->get();
        $meals = Meal::where('coach_id', auth::id())->where('status', '<>', 'declined')->with('type')->get();
        return view('pages.coach.show-cal-timeline', compact('items', 'timeline_id', 'meals', 'exercises'));
    }
    // public function show_timeline_add_item_view(int $timeline_id): View
    // {
    //     $meals = Meal::all();
    //     $meal_types = MealType::all();
    //     $exercises = Exercise::all();
    //     $exercise_types = ExerciseType::all();
    //     $days = Day::all();

    //     return view('pages.coach.add-timeline-item', compact('exercises', 'meals', 'days', 'timeline_id', 'meal_types', 'exercise_types'));
    // }

    public function show_all_meals(): View
    {
        $meals = Meal::where('coach_id', Auth::id())->with('type', 'qty_type')->get();
        return view('pages.coach.all_meals', compact('meals'));
    }
    public function add_new_meal(): View
    {
        $types = MealType::all();
        $qty_types = QuantityType::all();
        return view('pages.coach.new_meal', compact('types', 'qty_types'));
    }
    public function store_new_meal(Request $request)
    {
        $meal = Meal::create([
            "name" => $request->input('name'),
            "qty" => $request->input('qty'),
            "type_id" => $request->input('type'),
            "qty_type_id" => $request->input('qty_type'),
            "ingredients" => $request->input('ingredients'),
            "description" => $request->input('description'),
            "coach_id" => auth::id(),
        ]);
        // notify all admins.
        NotificationHelper::notifyAdmins(new NewMealRequestNotification($meal));
        return $this->returnMessage('Meal created successfully, waiting admin approval.');
    }
    public function store_new_exercise(Request $request)
    {
        $media_path = '';
        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $media_path = FileHelper::uploadToDocs($media, 'public/media');
        }
        $exercise = Exercise::create([
            "name" => $request->input('exerciseName'),
            "media" => $media_path,
            "coach_id" => Auth::id(),
            "type_id" => $request->input('exerciseType'),
            "muscle" => $request->input('targetedMuscle'),
            "equipment_id" => $request->input('equipment'),
            "description" => $request->input('exerciseDescription'),
            "duration" => $request->input('exerciseDuration'),
        ]);
        // notify all admins.
        NotificationHelper::notifyAdmins(new NewExerciseRequestNotification($exercise));
        return $this->returnMessage('Exercise created successfully, waiting admin approval.');
    }

    public function show_all_exercises(): View
    {
        $exercises = Exercise::with('type')->get();
        return view('pages.coach.all_exercises', compact('exercises'));
    }
    public function new_exerciese_view(): View
    {
        $equipments = ExerciseEquipment::all();
        $types = ExerciseType::all();
        $muscles = Exercise::muscles;
        return view('pages.coach.new_exercise', compact('equipments', 'types', 'muscles'));
    }
    public function new_timeline_store(CreateNewTimelineRequest $request)
    {
        DB::enableQueryLog();
        $gps = GoalPlanDisease::where('plan_id', $request->plan)
            ->where('goal_ids', $request->goals)
            ->where('disease_ids', $request->diseases)
            ->firstOr(function () use ($request) {
                return GoalPlanDisease::create([
                    "plan_id" => $request->plan,
                    "goal_ids" => $request->goals,
                    "disease_ids" => $request->diseases,
                ]);
            })->id;
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
    public function meal_delete(Request $request)
    {
        $meal = Meal::find($request->input('id'));
        if (empty($meal)) {

            return $this->returnError("Meal Not Found.", 404);
        }
        $meal->delete();
        return $this->returnMessage("Meal deleted successfully.");
    }
    public function trainee_chat($trainee_id)
    {
        $trainee = NormalUser::where('id', $trainee_id)->first();
        $coach = Coach::where('id', auth::id())->with('timelines.timeline_trainees')->first();
        $private_channel = $coach->privateChannel();
        // first find the conversation for this user and this trainee
        // on this coach channel
        // if did not exists create one and make this coach & trainee a member of it.
        $conversations = Conversation::where("channel_id", $private_channel->id)
            ->whereHas('members', fn ($q) => $q->where('user_id', $trainee_id))->get();
        //check if there any conversation.
        if ($conversations->isEmpty()) {
            // there are no conversations between this coach & this trainee.
            //make one
            $timeline = $coach->timelines->filter(fn ($item) => $item->where('trainee_id', $trainee_id))->values()->first();
            $conversation = Conversation::create([
                "name" => "$timeline->name-$trainee->fullname",
                "channel_id" => $private_channel->id,
                "type" => Conversation::ONE_ON_ONE_CONV,
            ]);
            ConversationMember::create([
                "conversation_id" => $conversation->id,
                "user_id" => $trainee_id,
            ]);
            ConversationMember::create([
                "conversation_id" => $conversation->id,
                "user_id" => $coach->id,
            ]);
        } else {
            $conversation = $conversations->first();
        }
        $message = $conversation->messages()->latest()->first();

        $chat = (object)[
            "avatar" => $conversation->avatar,
            "full_name" => $conversation->name,
            "last_msg" => $message?->message,
            "status" => $message?->status,
            "timestamp" => $message?->created_at->diffForHumans(),
            "timestamp_int" => Carbon::parse($message?->created_at)->unix(),
            "id" => $conversation->id,
            "channel_name" => $conversation->channel->name,
            "channel_type" => $conversation->channel->type,
            "my_message" => in_array($message?->member->user_id, [Auth::id()]),
        ];
        $channels = collect([$private_channel])->map(fn ($item) => (object)[$item->type => $item->name]); // private => Coach.3
        return view('pages.coach.one_trainee_chat', compact('channels', 'chat'));
    }
    public function chat_view(): View
    {
        $channels = Auth::user()->channels->map(fn ($item) => (object)[$item->type => $item->name]); // private => Coach.3
        $user = Auth::user();
        $coach = Coach::where('id', $user->id)->first();
        $allConversations = collect([]);
        foreach ($channels as $channel) {
            $channel = (array)$channel;
            $name = reset($channel);
            $conversations = Channel::where('name', $name)->first()->conversations;
            $allConversations = $allConversations->concat($conversations);
        }

        $chats = $allConversations->sortByDesc('message.created_at')->map(function (Conversation $conversation) {
            $message = $conversation->messages()->latest()->first();
            // check if coach is member of this conversation , if not , this means this conversation is new.
            // make this coach is member of this conversation.
            if (!in_array(Auth::id(), $conversation->members()->pluck('user_id')->toArray())) {
                info("This Coach not a member of this conversation : " . $conversation->name);
                ConversationMember::create([
                    "conversation_id" => $conversation->id,
                    "user_id" => Auth::id(),
                ]);
            }
            info( $conversation->avatar);
            return (object)[
                "avatar" => $conversation->avatar,
                "full_name" => $conversation->name,
                "last_msg" => $message?->message,
                "status" => $message?->status,
                "timestamp" => $message?->created_at->diffForHumans(),
                "timestamp_int" => Carbon::parse($message?->created_at)->unix(),
                "id" => $conversation->id,
                "channel_name" => $conversation->channel->name,
                "channel_type" => $conversation->channel->type,
                "my_message" => in_array($message?->member->user_id, [Auth::id()]),
            ];
        });
        return view('pages.coach.chat', compact('channels', 'chats'));
    }
    public function loadChat(Request $request)
    {
        $conversation = Conversation::where('id', $request->conversation)->first();

        if (empty($conversation)) {
            return $this->returnError("this chat are not avalible.", 404);
        }
        // check if this user is subscribed to this channel.
        $user = Auth::user();
        // check if you are a member of this $conversation
        if ($conversation->members()->where('user_id', Auth::id())->count() == 0) {
            return $this->returnError("you are not subscribed to this chat.", 403);
        }
        // load messages from this channel.
        $items = SubscriptionMessage::whereHas('member', fn ($query) => $query->where('conversation_id', $conversation->id))
            ->with('statuses', 'member.user')->get()->sortBy('created_at')->map(function (SubscriptionMessage $item) {
                // info($item);
                $statuses = $item->statuses()->whereHas('member', fn ($query) => $query->where('user_id', Auth::id()))
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
        info($items);
        return $this->returnData('chat', $items);
    }
    public function readMessage(Request $request)
    {
        $message_status_id = $request->input('message_status_id');
        $ms = MessageStatus::where('id', $message_status_id)->with('member')->get();
        if ($ms->isEmpty()) {
            return $this->returnError("Message Status Not Found.", 404);
        }
        $ms = $ms->first();
        if ($ms->member->user_id != Auth::id()) {
            return $this->returnError('This MS is not yours.', 403);
        }
        $ms->update(['status' => 'read']);
        return $this->returnMessage("MS Updated.");
    }
    public function readNewMessage(Request $request)
    {
        $status = MessageStatus::where('message_id', $request->input('message_id'))
            ->whereHas('member', fn ($query) => $query->where('user_id', Auth::id()))
            ->first();
        $status?->update(['status' => 'read']);
        return $this->returnMessage("MS Updated.");
    }
    public function newMessage(Request $request)
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
        $subscriptions_id = ConversationMember::where('conversation_id', $request->input('conversation'))
            ->where('user_id', '!=', $currentUser->id)->get()->pluck('id');
        // $users = User::whereIn('id',)
        // create status for all channel-subscription and this message as status default value is received.
        foreach ($subscriptions_id as $id) {
            MessageStatus::create([
                'message_id' => $message->id,
                'member_id' => $id,
            ]);
        }
    }
}

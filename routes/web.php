<?php

use App\Events\core\NewMessageEvent;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\ChatWebsocketController;
use App\Http\Controllers\coach\CoachController;
use App\Http\Controllers\user\AuthenticatedSessionController;
use App\Http\Controllers\user\RegisteredUserController;
use App\Models\ChannelSubscription;
use App\Models\MessageStatus;
use App\Models\SubscriptionMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/t', function () {
    $channel_id = 3;
    $messageText = "hello from test";
    //we have channel id and user id => subscription id 
    $subscription = ChannelSubscription::where('channel_id', $channel_id)
        ->where('user_id', Auth::id())->with('channel')->get();
    // check if this user is subscripted to this channel
    if ($subscription->isEmpty()) {
        return $this->returnError('you are not subuscripted to this channel.', 403);
    }
    $subscription = $subscription->first();
    $message = SubscriptionMessage::create([
        'subscription_id' => $subscription->id,
        'message' => $messageText
    ]);
    $currentUser = User::where('id', Auth::id())->first();
    // 1. send it as event to all subscribers
    event(new NewMessageEvent(
        $subscription->channel->name,
        $messageText,
        $message->id,
        $channel_id,
        Carbon::parse($message->created_at)->format('h:i A'),
        Carbon::parse($message->created_at)->diffForHumans(),
        $currentUser
    ));
    // 2. save the status as received.
    // get all this channel subscription
    $subscriptions_id = ChannelSubscription::where('channel_id', $channel_id)
        ->where('user_id', '!=', $currentUser->id)->get()->pluck('id');
    // create status for all channel-subscription and this message as status default value is received.
    foreach ($subscriptions_id as $id) {
        MessageStatus::create([
            'message_id' => $message->id,
            'subscription_id' => $id,
        ]);
    }
})->middleware('auth');
Route::get('/', [AuthenticatedSessionController::class, 'findNextRoute'])->middleware('auth');
Route::post('authenticate_websocket', [ChatWebsocketController::class, 'authenticateUser'])->middleware('auth');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('menu:admin');
Route::post('/login-store', [AuthenticatedSessionController::class, 'store'])->name('login.perform');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register-store', [RegisteredUserController::class, 'store'])->name('register.perform');
Route::group(['middleware' => ['auth']], function () {
    Route::post('/users/self/update', [AdminController::class, 'updateSelf'])->name('user.self.update');
    Route::post('/users/self/update-avatar', [AdminController::class, 'updateSelfAvatar'])->name('user.self.update.avatar');
});
Route::group(['middleware' => ['auth', 'menu:admin', 'type:admin'], 'prefix' => 'admin'], function () {
    Route::get('/', fn () => redirect('home'));
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::get('/admin-profile', [AdminController::class, 'adminMyProfile'])->name('admin.profile');
    Route::get('/users', [AdminController::class, 'allUsersView'])->name('users.all.view');
    Route::get('/users-create', [AdminController::class, 'createUserView'])->name('users.create.view');
    Route::post('/users-create', [AdminController::class, 'storeUser'])->name('new.user.store');

    Route::get('/requests/new-coach', [AdminController::class, 'newCoachRequestsView'])->name('requests.new.coach');
    Route::get('/requests/premium', [AdminController::class, 'permiumRequestsView'])->name('requests.premium');
    Route::get('/requests/meal', [AdminController::class, 'permiumRequestsView'])->name('requests.meal');

    Route::post('/requests/coach/change-status', [AdminController::class, 'changeStatusCoachRequest'])->name('admin.coach.request.change-status');
    Route::post('/requests/premium/change-status', [AdminController::class, 'changeStatusPremiumRequest'])->name('admin.permium.request.change-status');

    Route::post('/payment/qr/change', [AdminController::class, 'changeQR'])->name('admin.qr.update');
});

Route::group(['middleware' => ['auth', 'menu:coach', 'type:coach'], 'prefix' => 'coach'], function () {
    Route::get('home', [CoachController::class, 'home_view']);
    Route::get('chats', [CoachController::class, 'chat_view'])->name('chat');
    Route::post('chats/load', [CoachController::class, 'loadChat'])->name('chat.load');
    Route::get('/admin-profile', [CoachController::class, 'coachMyProfile'])->name('coach.profile');

    Route::post('chats/message/read', [CoachController::class, 'readMessage'])->name('chat.message.read');
    Route::post('chats/message/new/read', [CoachController::class, 'readNewMessage'])->name('chat.message.new.read');
    Route::post('chats/message/new', [CoachController::class, 'newMessage'])->name('chat.message.new');
});

Route::get('/sign-in-static', function () {
    return view('pages.sign-in-static');
})->name('sign-in-static');

Route::get('/profile-static', function () {
    return view('pages.profile-static');
})->name('profile-static');
Route::get('/sign-up-static', function () {
    return view('pages.sign-up-static');
})->name('sign-up-static');
Route::get('/profile', function () {
    return view('pages.profile');
})->name('profile');
Route::get('/page', function () {
    return view('pages.tables');
})->name('page')->middleware('menu:admin');
Route::get('/virtual-reality', function () {
    return view('pages.virtual-reality');
})->name('virtual-reality');
Route::get('/rtl', function () {
    return view('pages.rtl');
})->name('rtl')->middleware('menu:admin');

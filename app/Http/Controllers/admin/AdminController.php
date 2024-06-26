<?php

namespace App\Http\Controllers\admin;

use App\Models\Goal;
use App\Models\Meal;
use App\Models\User;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Exercise;
use App\Models\NormalUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\FileHelper;
use Illuminate\Http\JsonResponse;
use App\Models\UserPremiumRequest;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\admin\ChangeMealRequest;
use App\Http\Requests\admin\ChangeCoachRequest;
use App\Http\Requests\admin\ChangePremiumRequest;
use App\Http\Requests\admin\CreateNewUserRequest;
use App\Events\user\PremiumRequestStatusChangeEvent;
use App\Notifications\coach\ExerciseStatusChangeNotification;
use App\Notifications\coach\MealStatusChangeNotification;
use App\Notifications\coach\TraineeBecomeProNotification;

class AdminController extends Controller
{
    //
    public function allUsersView(): View
    {
        $users = User::whereNot('type', 'admin')->latest('updated_at')->latest('created_at')->get();
        return view('pages.admin.all-users', compact('users'));
    }
    public function adminMyProfile(): View
    {

        return view('pages.admin.admin-profile');
    }
    public function createUserView(): View
    {
        return view('pages.admin.create-user');
    }
    public function storeUser(CreateNewUserRequest $request)
    {
        info($request->all());
        if ($request->coach == 'on')
            $user = Coach::create($request->values());
        else
            $user = NormalUser::create($request->values());
        return back();
    }
    public function updateSelf(Request $request)
    {
        $user = User::where('id', Auth::id())->first();
        $user->update($request->all());
        return back();
    }
    public function updateSelfAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $name = FileHelper::uploadToDocs($avatar, 'public/avatars');
            $user = User::where('id', Auth::id())->first();
            $user->avatar = Str::replace('public', '', $name);
            $user->save();
            return $this->returnMessage('updated');
        }
        return $this->returnMessage('please upload image');
    }

    public function newCoachRequestsView(): View
    {
        $users = Coach::where('status', '=', 'waiting')->get();
        return view('pages.admin.new-coach-requests', compact('users'));
    }
    public function permiumRequestsView(): View
    {
        $premiumRequests = UserPremiumRequest::where('status', '=', 'pending')->get();
        return view('pages.admin.premium-requests', compact('premiumRequests'));
    }
    public function mealRequestsView(): View
    {
        $requests = Meal::where('status', '=', 'pending')->with('coach', 'type')->get();
        return view('pages.admin.meal-requests', compact('requests'));
    }

    public function exerciseRequestsView(): View
    {
        $requests = Exercise::where('status', '=', 'pending')->with('coach', 'type', 'equipment')->get();
        return view('pages.admin.exercise-requests', compact('requests'));
    }
    public function showCoachProfile(int $id): View
    {
        $user = Coach::where('id', $id)->with('timelines')->first();
        return view('pages.admin.coach-profile', compact('user'));
    }
    public function showUserProfile(int $id): View
    {
        $user = NormalUser::where('id', $id)->first();
        return view('pages.admin.user-profile', compact('user'));
    }

    public function changeStatusCoachRequest(ChangeCoachRequest $request): JsonResponse
    {
        $user = Coach::where('id', $request->input('id'))->first();
        $user->status = $request->input('status');
        $user->save();
        return $this->returnData("newStatus", $user->status, 'Coach Status Updated, New Status : ' . $user->status);
    }
    public function changeStatusPremiumRequest(ChangePremiumRequest $request): JsonResponse
    {
        $premiumRequest = UserPremiumRequest::where('id', $request->input('id'))->first();
        $premiumRequest->status = $request->input('status');
        $premiumRequest->update();
        $user = NormalUser::where('id', $premiumRequest->user_id)->first();
        event(new PremiumRequestStatusChangeEvent($user?->username, $premiumRequest->status));
        if ($premiumRequest->status == 'approved') {
            $coachTimeline = $user->enabled_timeline()?->timeline;
            $coachTimeline->coach->notify(new TraineeBecomeProNotification($user, $coachTimeline));
        }
        return $this->returnData("newStatus", $premiumRequest->status, 'Request Status Updated, New Status : ' . $premiumRequest->status);
    }
    public function changeStatusMealRequest(Request $request): JsonResponse
    {
        $meal = Meal::where('id', $request->input('id'))->first();
        if (empty($meal))
            return $this->returnError('Meal not found.', 404);
        $meal->status = $request->input('status');
        $meal->update();
        $coach = Coach::find($meal->coach_id);
        $coach->notify(new MealStatusChangeNotification($meal));
        return $this->returnData("newStatus", $meal->status, 'Request Status Updated, New Status : ' . $meal->status);
    }

    public function changeStatusExerciseRequest(Request $request): JsonResponse
    {
        $exercise = Exercise::where('id', $request->input('id'))->first();
        if (empty($exercise))
            return $this->returnError('Exercise not found.', 404);
        $exercise->status = $request->input('status');
        $exercise->save();
        $coach = Coach::find($exercise->coach_id);
        $coach->notify(new ExerciseStatusChangeNotification($exercise));
        return $this->returnData("newStatus", $exercise->status, 'Request Status Updated, New Status : ' . $exercise->status);
    }


    public function changeStatusUser(Request $request): JsonResponse
    {
        $user = User::where('id', $request->input('id'))->first();
        if (empty($user))
            return $this->returnError('user not found.', 404);
        $user->status = $request->input('status');
        $user->save();
        return $this->returnData("newStatus", $user->status, 'User Status Updated, New Status : ' . $user->status);
    }


    public function changeQR(Request $request)
    {
        if ($request->hasFile('new-qr-image')) {
            $new_qr_image = $request->file('new-qr-image');
            $url = FileHelper::uploadToDocs($new_qr_image, 'public/payment_qr', 'QR.jpg');
            return $this->returnData('new-img', $url, 'QR Image has been changed.');
        } else {
            return $this->returnError('no image found.', 401);
        }
    }
}

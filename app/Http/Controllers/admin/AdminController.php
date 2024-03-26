<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\FileHelper;
use App\Http\Requests\admin\ChangeCoachRequest;
use App\Http\Requests\admin\ChangePremiumRequest;
use App\Http\Requests\admin\CreateNewUserRequest;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Meal;
use App\Models\User;
use App\Models\UserPremiumRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        // info($request->all());
        $user = User::create($request->values());
        return back();
    }
    public function updateSelf(Request $request)
    {
        $user = Admin::where('id', Auth::id())->first();
        $user->update($request->all());
        return back();
    }
    public function updateSelfAvatar(Request $request)
    {
        $avatar = $request->file('avatar');
        $name = FileHelper::uploadToDocs($avatar, 'public/avatars');
        $user = Admin::where('id', Auth::id())->first();
        $user->avatar = Str::replace('public', '', $name);
        $user->update();
        return $this->returnMessage('updated');
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
        $requests = Meal::where('status', '=', 'pending')->get();
        return view('pages.admin.meal-requests', compact($requests));
    }
    public function changeStatusCoachRequest(ChangeCoachRequest $request): JsonResponse
    {
        $user = Coach::where('id', $request->input('id'))->first();
        $user->status = $request->input('status');
        $user->update();
        return $this->returnData("newStatus", $user->status, 'Coach Status Updated, New Status : ' . $user->status);
    }
    public function changeStatusPremiumRequest(ChangePremiumRequest $request): JsonResponse
    {
        $premiumRequest = UserPremiumRequest::where('id', $request->input('id'))->first();
        $premiumRequest->status = $request->input('status');
        $premiumRequest->update();
        return $this->returnData("newStatus", $premiumRequest->status, 'Request Status Updated, New Status : ' . $premiumRequest->status);
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

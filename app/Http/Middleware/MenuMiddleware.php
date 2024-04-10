<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Illuminate\Support\Facades\View;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, mixed $argument): Response
    {

        if (!empty($argument)) {
            // this argument will be user type
            switch ($argument) {
                case 'admin':
                    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
                    View::share('menuData', [json_decode($verticalMenuJson)]);
                    $me = Admin::where('id', auth::id())->first();
                    $notifications = $me->unreadNotifications->map(function ($item) {
                        $types = explode('\\', $item->type);
                        return  [
                            'id' => $item->id,
                            'type' => end($types),
                            'data' => $item->data,
                            'is_read' => $item->read_at != null,
                            'created_at' => $item->created_at->diffForHumans(),
                        ];
                    });
                    info($me->unreadNotifications->toArray());
                    View::share('notifications', $notifications);
                    break;
                case 'coach':
                    $verticalMenuJson = file_get_contents(base_path('resources/menu/coachVerticalMenu.json'));
                    View::share('menuData', [json_decode($verticalMenuJson)]);
                    break;

                default:
                    $nonAdminVerticalMenu = file_get_contents(base_path('resources/menu/nonAdminVerticalMenu.json'));
                    View::share('menuData', [json_decode($nonAdminVerticalMenu)]);
                    break;
            }
        } else {
            // load all and base on user type pass menu data.
            $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
            $nonAdminVerticalMenu = file_get_contents(base_path('resources/menu/nonAdminVerticalMenu.json'));


            $verticalMenuData = json_decode($verticalMenuJson);
            $nonAdminVerticalMenuData = json_decode($nonAdminVerticalMenu);
            // Share all menuData to all the views
            if (Auth::user()->type == 'admin')
                View::share('menuData', [$verticalMenuData]);
            else
                View::share('menuData', [$nonAdminVerticalMenuData]);
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\NotificationCollection;

class NotificationController extends Controller
{
    /**
     * Get the unread notifications of the authenticated user.
     *
     * @param Request $request
     * @return mixed
     */
    public function getUnread(Request $request): mixed
    {
        return $request->user()->unreadNotifications;
    }

    public function get(Request $request)
    {
        return new NotificationCollection($request->user()->notifications()->paginate(5));
    }
}

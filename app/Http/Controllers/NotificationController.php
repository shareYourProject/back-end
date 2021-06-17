<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationCollection;

class NotificationController extends Controller
{
    /**
     * Get the unread notifications of the authenticated user.
     *
     * @param Request $request
     * @return \NotificationCollection
     */
    public function getUnread(Request $request): \NotificationCollection
    {
        return new NotificationCollection($request->user()->unreadNotifications);
    }

    /**
     * Get all the notification of the authenticated user.
     *
     * @param Request $request
     * @return NotificationCollection
     */
    public function get(Request $request): NotificationCollection
    {
        return new NotificationCollection($request->user()->notifications()->paginate(5));
    }

    /**
     * Mark a notification as read.
     *
     * @param Request $request
     * @param String $id
     * @return JsonResponse
     */
    public function markAsRead(Request $request, String $id): JsonResponse
    {
        foreach ($request->user()->unreadNotifications as $notification) {
            if ($notification->id == $id) {
                $notification->markAsRead();

                return new JsonResponse();
            }
        }

        abort(404);
    }
}

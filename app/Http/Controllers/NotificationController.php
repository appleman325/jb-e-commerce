<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Update/create an existing/new unseen notification
     * based on user_id and notification type.
     * This will be useful when a user has multiple
     * same type (e.g., product_approved) unseen notifications.
     * These same type unseen notifications can be combined as
     * one notification in the notifications table.
     * Their notification data can be saved as json in the data column.
     *
     * POST /users/{user_id}/notifications
     *
     * @return App\Models\Notification
     */
    public function create($user_id, Request $request)
    {
        $this->validate($request, [
            'type' => 'required|in:product_depleted,product_status_change,product_approved'
        ]);

        // Update or create an existing/new notifcation
        // based on user_id and notification type
        $notification = Notification::whereNull('read_at')
            ->updateOrCreate([
                'user_id' => $user_id,
                'type' => $request->type
            ], [
                'user_id' => $user_id,
                'type' => $request->type,
                'data' => $request->data
            ]);

        return response()->json([
            'message' => 'Notification created/updated successfully!',
            'notification' => $notification
        ]);
    }

    /**
     * Get all unseen notifications for a user
     *
     * GET /users/{user_id}/notifications
     *
     * @return App\Models\Notification
     */
    public function getNewNotifications($user_id)
    {
        $notifications = Notification::where('user_id', $user_id)
            ->whereNull('read_at')
            ->get();

        return response()->json([
            'message' => 'New notifications retrieved successfully!',
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as seen
     *
     * POST /users/{user_id}/notifications/{notification_id}
     *
     * @return App\Models\Notification
     */
    public function readNotification($user_id, $notification_id)
    {
        $notification = Notification::where('user_id', $user_id)
            ->where('id', $notification_id)
            ->first();

        if ($notification) {
            $notification->update(['read_at' => Carbon::now()->format('Y-m-d H:i:s')]);

            return response()->json([
                'message' => 'Notification updated successfully!',
                'notification' => $notification
            ]);
        }

        return response()->json([
            'message' => 'Notification not found!'
        ], 404);
    }

}

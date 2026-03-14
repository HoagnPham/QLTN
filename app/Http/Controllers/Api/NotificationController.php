<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\SendNotificationRequest;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function send(SendNotificationRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $rows = [];

        foreach ($payload['user_ids'] as $userId) {
            $rows[] = [
                'user_id' => $userId,
                'title' => $payload['title'],
                'message' => $payload['message'],
                'type' => $payload['type'],
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Notification::query()->insert($rows);

        return response()->json(['message' => 'Notifications sent', 'count' => count($rows)]);
    }

    public function myNotifications(Request $request): JsonResponse
    {
        $user = $request->attributes->get('auth_user');

        return response()->json(Notification::query()->where('user_id', $user->id)->latest()->paginate(20));
    }
}

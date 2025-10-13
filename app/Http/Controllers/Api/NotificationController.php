<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\CustomerInfo;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $user = $user_id ? User::find($user_id) : null;

        $notifications = Notification::query()
            ->where('is_general', true)
            ->when($user, function ($query) use ($user) {
                $query->where('created_at', '>=', $user->created_at);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $notifications->merge($user->notifications());
        $merged = $notifications->sortByDesc('created_at')->values();



        $data = $merged->map(function ($notification) {
            return [
                'id'         => $notification->id,
                'title'      => $notification->title,
                'body'       => $notification->body,
                'photo'      => $notification->photo,
                'created_at' => $notification->created_at,
            ];
        });

        return response()->json(
            [
                'message' => 'done, fetched successfully',
                'code' => '200',
                "data" => $data
            ]
        );
    }

    public function saveCustomerToken(Request $request)
    {
        $request->validate([
            'notification_token' => 'required|string',
        ]);


        $customerInfo = CustomerInfo::where('user_id', $request->user()->id)->first();

        if (!$customerInfo)
        {
            return response()->json([
                'message' => 'Customer info not found.',
            ], 404);
        }

        $customerInfo->notification_token = $request->notification_token;
        $customerInfo->save();

        return response()->json([
            'message' => 'Notification token saved successfully.',
            'data'    => $customerInfo->only(['user_id', 'notification_token']),
        ], 200);
    }
}

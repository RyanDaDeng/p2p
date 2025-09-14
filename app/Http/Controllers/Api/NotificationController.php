<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Common\ApiController;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends ApiController
{
    /**
     * Get notifications via API
     */
    public function index(Request $request)
    {
        $notifications = Notification::with('order')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($notifications);
    }

}

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
        $query = Notification::with('order')
            ->where('user_id', auth()->id());

        // Apply filter
        $filter = $request->get('filter', 'all');
        if ($filter === 'read') {
            $query->where('is_read', true);
        } elseif ($filter === 'unread') {
            $query->where('is_read', false);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($notifications);
    }

}

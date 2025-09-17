<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class FireblocksWebhookWebController extends Controller
{

    public function receivePayment(Request $request)
    {
        $log = Log::channel('fireblocks');

        try {
            $payload = $request->all();

            $log->info('Processing webhook payload', [
                'type' => $payload['type'] ?? null,
                'data' => $payload
            ]);

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            $log->error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'reason' => $e->getMessage()], 500);
        }
    }
}

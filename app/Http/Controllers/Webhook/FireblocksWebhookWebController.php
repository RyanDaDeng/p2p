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
        $payload = $request->json();

        $log->info('Processing webhook payload', $payload);

        return response()->json(['status' => 'success'], 200);
    }
}

<?php

namespace App\Http\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class FireblocksWebhookWebController extends Controller
{

    public function receivePayment(Request $request)
    {
        $log = Log::channel('fireblocks');

        $log->info($request->json());
        return response('success');
    }
}

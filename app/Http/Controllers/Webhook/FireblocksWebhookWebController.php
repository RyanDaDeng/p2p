<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\FireblocksTransaction;
use App\Models\Order;
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class FireblocksWebhookWebController extends Controller
{

    public function receivePayment(Request $request)
    {
        $log = Log::channel('fireblocks');
        try {
            $payload = $request->all();
            $log->info('IP', [$request->ip()]);
            $log->info('Processing webhook payload', $payload);

            if (isset($payload['fireblocks_tx_id'])) {
                FireblocksTransaction::updateOrCreate(
                    ['fireblocks_tx_id' => $payload['fireblocks_tx_id']],
                    [
                        'tx_hash' => $payload['tx_hash'] ?? null,
                        'status' => $payload['status'] ?? '',
                        'asset' => $payload['asset'] ?? '',
                        'quantity' => $payload['quantity'] ?? '',
                        'source' => $payload['source'] ?? null,
                        'source_address' => $payload['source_address'] ?? null,
                        'destination' => $payload['destination'] ?? null,
                        'destination_address' => $payload['destination_address'] ?? null,
                        'third_party_order_no' => $payload['third_party_order_no'] ?? null,
                        'raw_data' => $payload,
                    ]
                );
            }

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

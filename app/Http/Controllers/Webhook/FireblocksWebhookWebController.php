<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
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

            $side = $this->getSide($payload['fireblocks_tx_id']);

            if($side === 'in'){
                $log->info('Processing RECEIVE webhook payload', $payload);
                $res = self::checkReceiveOrder(
                    $payload['status'],
                    $payload['tx_hash'],
                    $payload['source_address'],
                    $payload['destination_address'],
                    (float) $payload['quantity']
                );

                if ($res !== false) {
                    $escrowService = new EscrowService();
                    $escrowService->confirmEscrowReceived($res, $payload);
                } else {
                    // todo 有未知的转账，需要提醒TG
                }
            } else {
                $log->info('Processing SEND webhook payload', $payload);
                $res = self::checkSendOrder(
                    $payload['status'],
                    $payload['fireblocks_tx_id'],
                    $payload['source_address'],
                    $payload['destination_address'],
                );

                if ($res !== false) {
                    $escrowService = new EscrowService();
                    $escrowService->escrowReleased($res, $payload['tx_hash'], $payload);
                } else {
                    // todo 有未知的转账，需要提醒TG
                }
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

    private function getSide($txnId){
        return Order::query()->where('release_tx_id', $txnId)->exists() ? 'out' : 'in';
    }

    public static function checkReceiveOrder(
        $status, $txnHash, $sourceAddress, $destinationAddress, float $quantity
    ){
        if ($status == 'COMPLETED'){
            $order = Order::query()
                ->where('escrow_tx_hash', $txnHash)
                ->where('status', 'trading')
                ->where('escrow_status', 'seller_paid')
                ->where('seller_address', $sourceAddress)
                ->where('escrow_address', $destinationAddress)
                ->where('is_disputed', 0)
                ->first();

            if ($order && $quantity >= (float) $order->crypto_amount) {
                return $order->id;
            } else {
                return false;
            }
        }
        return false;
    }


    public static function checkSendOrder(
        $status, $fireblocksTxnId, $sourceAddress, $destinationAddress
    ){
        if ($status == 'COMPLETED'){
            $order = Order::query()
                ->where('release_tx_id', $fireblocksTxnId)
                ->where('status', 'trading')
                ->where('escrow_status', 'seller_received')
                ->where('buyer_address', $destinationAddress)
                ->where('escrow_address', $sourceAddress)
                ->where('is_disputed', 0)
                ->first();
            return $order->id;
        }
        return false;
    }
}

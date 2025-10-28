<?php

namespace App\Console\Commands;

use App\Models\FireblocksTransaction;
use App\Models\Order;
use App\Services\EscrowService;
use Illuminate\Console\Command;

class TxnCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:txn-check-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = FireblocksTransaction::query()
            ->whereNull('order_id')
            ->where('is_checked',false)
            ->where('status','COMPLETED')
            ->get();

        foreach ($transactions as $transaction){
            $payload = $transaction->toArray();
            //
            $side = $this->getSide($payload['fireblocks_tx_id']);

            if($side === 'in'){
                $res = self::checkReceiveOrder(
                    $payload['status'],
                    $payload['tx_hash'],
                    $payload['source_address'],
                    $payload['destination_address'],
                    (float) $payload['quantity']
                );

                if ($res !== false) {
                    $transaction->update([
                       'order_id' => $res,
                       'is_checked' => true
                    ]);
                    $escrowService = new EscrowService();
                    $escrowService->confirmEscrowReceived($res, $payload);
                } else {
                    // todo 有未知的转账，需要提醒TG
                }
            } else {
                $res = self::checkSendOrder(
                    $payload['status'],
                    $payload['fireblocks_tx_id'],
                    $payload['source_address'],
                    $payload['destination_address'],
                );

                if ($res !== false) {
                    $transaction->update([
                        'order_id' => $res,
                        'is_checked' => true
                    ]);
                    $escrowService = new EscrowService();
                    $escrowService->escrowReleased($res, $payload['tx_hash'], $payload);
                } else {
                    // todo 有未知的转账，需要提醒TG
                }
            }
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

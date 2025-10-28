<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Services\FireBlocksService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AddressCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:address-check-command';

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
        //
        $data = Address::query()
            ->where('status', Address::PENDING_STATUS)
            ->get();
        $service = new \App\Services\FireBlocksService();

        foreach ($data as $datum){
            $datum->last_checked = Carbon::now()->format('Y-m-d H:i:s');

            if(empty($datum->fireblocks_status_ref) && $datum->fireblocks_status == FireBlocksService::REJECTED_STATUS){
                $datum->status = Address::DECLINED_STATUS;
                $datum->save();
                continue;
            }

            if (empty($datum->fireblocks_status_ref)) {
                continue;
            }

            $res = $service->checkStatus($datum->fireblocks_status_ref);

            if (
                $res['success'] === true
            ) {
                $datum->fireblocks_report = $res['compliance_report'];
                $datum->fireblocks_status = $res['compliance_status'];

                if ($res['compliance_status'] == FireBlocksService::APPROVED_STATUS){
                    $assetId = $service->getAssetId($datum->currency_key);
                    //register
                   $registerRes = $service->registerAddress(
                        $datum->user_id,
                        $assetId,
                        $datum->address,
                        ''
                    );

                   if ($registerRes['success'] === true) {
                       $datum->status = Address::REGISTERED_STATUS;
                   }
                }

                if ($res['compliance_status'] == FireBlocksService::REJECTED_STATUS){
                    $datum->status = Address::DECLINED_STATUS;
                }

                if ($res['compliance_status'] == FireBlocksService::UNKNOWN_STATUS){
                    $datum->status = Address::UNKNOWN_STATUS;
                }
            } else {
                $datum->status = Address::FAILURE_STATUS;
            }
            $datum->save();
        }
    }
}

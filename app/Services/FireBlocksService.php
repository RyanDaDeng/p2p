<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\delete;

class FireBlocksService
{
    private $api = 'http://8.138.144.190:8080/';

    const PENDING_STATUS = 0;
    const APPROVED_STATUS = 1;

    const REJECTED_STATUS = 2;
    const UNKNOWN_STATUS = -1;
    private const FIRE_BLOCKS_TOKEN = 'FIRE_BLOCKS_TOKEN';
    private function generateToken(){
       $res = Http::baseUrl($this->api)->post('/oauth2/token', [
            'api_key'=> 'A7K4Z9T2M1P8Q3R6Y5L0X2D7S8C4H6N1'
        ]);
       $data = $res->json();
       if (isset($data['access_token'])) {
           Cache::set(self::FIRE_BLOCKS_TOKEN, $data['access_token'], 3500);
       }
       return $data['access_token'];
    }


    public function getComplianceStatus($code){
        switch($code){
            case self::PENDING_STATUS:
                return 'PENDING';
            case self::REJECTED_STATUS:
                return 'REJECTED';
            case self::APPROVED_STATUS:
                return 'APPROVED';
            default:
                return 'UNKNOWN';
        }
    }

    public function getAssetId($key): ?string
    {
        if(config('fireblocks.sandbox')){
            return $this->getAssetTest($key);
        } else {
            return $this->getAssetProd($key);
        }
    }


    public function getEscrowAddress($key) {
        if(config('fireblocks.sandbox')){
            return $this->getEscrowTest($key);
        } else {
            return $this->getEscrowProd($key);
        }
    }

    private function getEscrowTest($key){
        switch($key){
            case 'trc-usdt':
                return 'TUxYnLQWxPms3hE4JAYedxxJD77LdbsE99';
            default:
                return null;
        }
    }

    private function getEscrowProd($key){
        return null;
    }
    private function getAssetTest($key): ?string
    {
        switch($key){
            case 'trc-trx':
                return 'TRX_TEST';
            default:
                return null;
        }
    }
    private function getAssetProd($key): ?string
    {
        switch($key){
            case 'erc-usdt':
                return 'USDT_ERC';
            case 'trc-usdt':
                return 'USDT_TRC';
            case 'bep-usdt':
                return 'USDT_BEP';
            case 'erc-usdc':
                return 'USDC_ERC';
            case 'bep-usdc':
                return 'USDC_BEP';
            case 'btc':
                return 'BTC';
            case 'eth':
                return 'ETH';
            default:
                return null;
        }
    }
    private function getToken(){
        $token = Cache::get(self::FIRE_BLOCKS_TOKEN);

        if ($token){
            return $token;
        }

        return $this->generateToken();
    }

    public function removeAddress(
        string $userId,
        string $assetId,
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/addresses/removal', [
                'user_id' => $userId,
                'asset_id' => $assetId,
            ])
            ->json();
    }


    public function getVaultAssets(){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->get('/vault/assets')
            ->json();
    }

    public function getVaultAddresses(
        string $userId,
        string $assetId,
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->get('/vault/addresses')
            ->json();
    }


    public function complianceAddressCheck(
        string $userId,
        string $assetId,
        string $address,
        string $tag
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/addresses/ComplianceAddressCheck', [
                'user_id' => $userId,
                'asset_id' => $assetId,
                'address' => $address,
                'tag'=> $tag
            ])
            ->json();
    }

    public function checkInFireblocks(
        string $userId,
        string $assetId,
        string $address,
        string $tag
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/addresses/checkInFireblocks', [
                'user_id' => $userId,
                'asset_id' => $assetId,
                'address' => $address,
                'tag'=> $tag
            ])
            ->json();
    }

    public function searchTransaction(
        $source,
        $destination,
        $assetId,
        $txHash
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/transactions/search', [
                'asset_id' => $assetId,
                'source_address' => $source,
                'destination_address' => $destination,
                'tx_hash' => $txHash,
            ])
            ->json();
    }

    public function transactionDetail(
        $fireBlocksTxId,
        $currency,
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/transactions/detail', [
                'fireblocks_tx_id' => $fireBlocksTxId,
                'currency' => $currency,
            ])
            ->json();
    }

    public function applyPayment( $clientId,
                                      $payeeId,
                                      $address,
                                      $asset,
                                      $quantity,
                                      $tag = null){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/payments/apply', [
                'third_party_order_no' => $clientId,
                'payee_id' => $payeeId,
                'address' => $address,
                'asset' => $asset,
                'quantity' => $quantity,
                'tag'=> $tag
            ])
            ->json();
    }
    public function registerAddress(
        string $userId,
        string $assetId,
        string $address,
        string $tag
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/addresses/register', [
                'user_id' => $userId,
                'asset_id' => $assetId,
                'address' => $address,
                'tag'=> $tag
            ])
            ->json();
    }

    public function deleteAddress(
        string $userId,
        string $assetId,
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/addresses/removal', [
                'user_id' => $userId,
                'asset_id' => $assetId,
            ])
            ->json();
    }


    public function checkStatus(
        string $analyseId,
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/addresses/status', [
                'analysis_id' => $analyseId,
            ])
            ->json();
    }

    public function getVaultAddress(
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->get('/vault/addresses')
            ->json();
    }


    public function getTransactionDetail(
        string $txHash,
        string $currency
    ){
        $token = $this->getToken();
        return Http::baseUrl($this->api)
            ->withToken($token)
            ->post('/transactions/detail', [
                'tx_hash' => $txHash,
                'currency' => $currency
            ])
            ->json();
    }

    public function getAssets(){
        $token = $this->getToken();
        return Http::baseUrl($this->api)->withToken($token)->get('/vault/assets')->json();
    }
}

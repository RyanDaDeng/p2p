<?php

return [
    'sandbox' => env('FIRBLOCKS_SANDBOX', true),
    'escrow' => [
        'prod'=> [
        ],
        'test'=> [
            'trc-trx' => 'TUxYnLQWxPms3hE4JAYedxxJD77LdbsE99'
        ],
    ],
    'assets' => [
        'prod' => [

        ],
        'test' => [
            'trc-trx' => 'TRX_TEST'
        ]
    ]
];

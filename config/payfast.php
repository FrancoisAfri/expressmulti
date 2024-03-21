<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return

        /*
          |--------------------------------------------------------------------------
          | Merchant Settings
          |--------------------------------------------------------------------------
          | All Merchant settings below are for example purposes only. for more info
          | see www.payfast.co.za. The Merchant ID and Merchant Key can be obtained
          | from your payfast.co.za account.
          |
         */
        [
            'testing' => false, // Set to false when in production.
            'merchant' => [
                'merchant_id' => env('PF_MERCHANT_ID', '11292830'), // TEST Credentials. Replace with your merchant ID from Payfast.
                'merchant_key' => env('PF_MERCHANT_KEY', 'nxzeqol0ppi7s'), // TEST Credentials. Replace with your merchant key from Payfast.
                'return_url' => env('PF_RETURN_URL', 'http://expresserve.afrixcel.co.za/success'), // Redirect URL on Success.
                'cancel_url' => env('PF_CANCEL_URL', 'http://expresserve.afrixcel.co.za/card-cancelled'), // Redirect URL on Cancellation.
                'notify_url' => env('PF_ITN_URL', 'http://expresserve.afrixcel.co.za/itn'), // ITN URL.
            ],
            'hosts' => [
                'www.payfast.co.za',
                'sandbox.payfast.co.za',
                'w1w.payfast.co.za',
                'w2w.payfast.co.za',
            ]
];

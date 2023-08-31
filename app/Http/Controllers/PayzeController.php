<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PayzeController extends Controller
{
    public function saveCardInfo(Request $request)
    {
        $client = new Client();
        
        $response = $client->request('POST', 'https://payze.io/api/v1', [
            'body' => json_encode([
                'method' => 'addCard',
                'apiKey' => 'C0D3BC5148CC428FB42B678783EBAE75',
                'apiSecret' => '372C24A0ED894C79A4EEEE4045E9DBEB',
                'data' => [
                    'callback' => 'http://localhost:8000/api/payze-callback', // date this URL
                    'callbackError' => 'http://localhost:8000/api/payze-callback', 
                    'hookUrl' => 'http://localhost:8000/payze_hook?authorization_token=token',
                    'hookUrlV2' => 'http://localhost:8000/payze_hook?authorization_token=token',
                    'amount' => 0,
                    'currency' => 'GEL',
                    'hookRefund' => false,
                ],
            ]),
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);
        
        $payzeResponseForRedirect = json_decode($response->getBody(), true);
        $transactionUrl = $payzeResponseForRedirect['response']['transactionUrl'];
        
        return $transactionUrl;
    }

    public function payzeCallback(Request $request)
    {
        // Get the payment_transaction_id from the URL
        $payment_transaction_id = $request->query('payment_transaction_id');
        
        // Get the callback data from the request
        $callbackData = $request->all(); // Adjust based on Payze's callback data format

        // Use the captured payment_transaction_id in your processing logic
        // For example, update payment status, log transactions, etc.
       
        // Return a response indicating successful processing
        return response()->json(['message' => 'Callback processed successfully',$callbackData  ]);
    }
}

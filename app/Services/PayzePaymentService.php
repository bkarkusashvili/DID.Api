<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayzePaymentService
{
    private static $api = 'https://payze.io/v2/api/';

    public static function createProduct()
    {
        $res = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => env('PAYZE_KEY') . ':' . env('PAYZE_SECRET'),
        ])->post(PayzePaymentService::$api . 'product', [
            'name' => 'Product name',
            'description' => 'Product Description',
            'imageUrl' => 'https://payze.io?imageId=12',
            'price' => 50,
            'currency' => 'GEL',
            'occurrenceType' => 'Day',
            'occurrenceNumber' => 1,
            'occurrenceDuration' => 1,
            'freeTrial' => 5,
            'numberOfFailedRetry' => '3'
        ]);

        $data = json_decode($res->body());

        return $data;
    }

    public static function getProduct()
    {
        $res = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => env('PAYZE_KEY') . ':' . env('PAYZE_SECRET'),
        ])->get(PayzePaymentService::$api . 'products', [
            // 'name' => 'Product name',
            // 'description' => 'Product Description',
            // 'imageUrl' => 'https://payze.io?imageId=12',
            // 'price' => 50,
            // 'currency' => 'GEL',
            // 'occurrenceType' => 'Day',
            // 'occurrenceNumber' => 1,
            // 'occurrenceDuration' => 1,
            // 'freeTrial' => 5,
            // 'numberOfFailedRetry' => '3'
        ]);

        $data = json_decode($res->body());

        return $data;
    }

    public static function createSubscription()
    {
        $res = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => env('PAYZE_KEY') . ':' . env('PAYZE_SECRET'),
        ])->post(PayzePaymentService::$api . 'subscription', [
            'productId' => 470,
            // 'cardToken' => 'PAY123ZE...',
            'hookUrl' => 'https://payze.io',
            'email' => 'info@payze.ge',
            'phone' => '',
            'callback' => 'https://payze.io',
            'callbackError' => 'https://payze.io/error',
            'sendEmails' => false
        ]);

        $data = json_decode($res->body());

        return $data;
    }

    public static function getSubscription()
    {
        $res = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => env('PAYZE_KEY') . ':' . env('PAYZE_SECRET'),
        ])->get(PayzePaymentService::$api . 'subscriptions', [
            // 'ProductId' => 470,
        ]);


        $data = json_decode($res->body());

        return $data;
    }
}

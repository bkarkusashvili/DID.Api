<?php

namespace App\Http\Controllers;

use App\Services\PayzePaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function createProduct()
    {
        return PayzePaymentService::createProduct();
    }

    public function getProduct()
    {
        return PayzePaymentService::getProduct();
    }

    public function createSubscription()
    {
        return PayzePaymentService::createSubscription();
    }

    public function getSubscription()
    {
        return PayzePaymentService::getSubscription();
    }
}

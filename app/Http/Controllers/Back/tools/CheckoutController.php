<?php

namespace App\Http\Controllers;


class CheckoutController extends Controller
{
    public function index()
    {
        // $order = [[]'total' => 100, 'id' => 1];
        $PaymentKey = PaymobFinalController::pay(300, 1);
        return view('payment.paymob_iframe', ['token' => $PaymentKey]);
    }
}

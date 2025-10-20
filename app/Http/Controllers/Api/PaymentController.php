<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Nafezly\Payments\Classes\PaymobPayment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function pay($data)
    {


        $payment = new PaymobPayment();

        $response = $payment->pay($data['amount'], $data['user_id'], $data['user_first_name'], $data['user_last_name'], $data['user_email'], $data['user_phone']);



        // return response()->json([
        //     'iframe_url' => $response['redirect_url']
        // ]);

        return $response;
    }

        public function callback(Request $request)
        {

            $payment = new PaymobPayment();


            $result = $payment->verify($request);

            dd($result);
            $paymentRecord = Payment::where('provider_payment_id', $result['payment_id'])->first();

            if ($paymentRecord) {
                $paymentRecord->update([
                    'status' => $result['success'] ? 'paid' : 'failed',
                    'response_data' => json_encode($result['process_data']),
                ]);


                if ($result['success']) {

                    $paymentRecord->order->update(['payment_status' => 'paid']);

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment successful',
                        'transaction_id' => $result['payment_id']
                    ]);
                } else {

                    $paymentRecord->order->update(['payment_status' => 'fail']);
                    return response()->json([
                        'success' => false,
                        'message' => $result['message']
                    ]);
                }
            }
        }
}

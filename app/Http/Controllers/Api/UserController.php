<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function purchase(Request $request)
    {
        $user = User::firstOrCreate(
            [
                'email' => $request->input('email')
            ],
            [
                'password' => Hash::make(Str::random(12)),
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zip_code' => $request->input('zip_code')
            ]
        );
        try {
            $payment = $user->charge($request->input('amount'), $request->input('payment_method_id'));
            $payment = $payment->asStripePaymentIntent();

            $order = $user->orders()->create(['transaction_id' => $payment->charges->data[0]->id, 'total' => $payment->charges->data[0]->amount]);
            foreach (json_decode($request->input('cart'), true) as $item) {
                $order->products()->attach($item['id'], ['quantity' => $item['quantity']]);
            }
            $order->load('products');
            return $order;
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function bestaf(Request $request)
    {
        // return $request;
        if ($request['payment_meta']['status'] != 'success') {
            return response()->json(['message' => "Ouch!"]);
        }
        $paymentDetails = $request['payment_meta']['data'];
        $user = User::firstOrCreate(
            [
                'email' => $paymentDetails['customer']['email']
            ],
            [
                'password' => Hash::make(Str::random(12)),
                'name' => $paymentDetails['customer']['name'],
                'address' => Str::random(3) . ' Street',
                'city' => 'City' . Str::random(2),
                'state' => 'State ' . Str::random(1),
                'zip_code' => Str::random(3),
            ]
        );
        try {
            $order = $user->orders()->create(['transaction_id' => $paymentDetails['tx_ref'], 'total' => $paymentDetails['amount']]);
            foreach (json_decode($request->input('cart'), true) as $item) {
                $order->products()->attach($item['id'], ['quantity' => $item['quantity']]);
            }
            $order->load('products');
            return $order;
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
        // $data = array("message" => "Complete", "paymentdetails" => $paymentDetails);
        // return response()->json($data);
        // $sampleRequestData = array("id" => $request->, "tx_ref" => "tr_682529207", "bestaf_ref" => "bestaf_1665784337");
        // $sample = array("id" => 638740570, "tx_ref" => "tr_682529207", "bestaf_ref" => "bestaf_1665784337");
        //return response()->json(['message' => 'Empty test']);
        // return response()->json(['message' => 'Data Test', 'data' => $sample]);
        // $user = User::firstOrCreate(
        //     ['email' => $request->input('email')],
        //     ['password' => Hash::make(Str::random(12)), 'name' => $request('fullname'), 'address' => 'Random address parameter', 'city' => 'Lagos', 'state' => 'Lagos', 'zip_code' => '120222']
        // );

        // try {
        //     // // check if there is a response
        //     // $paymentDetails = $request('data');
        //     // if ($paymentDetails['data']['success'] == 'success') {
        //     //     return response()->json(['message' => 'Hurray']);
        //     // }
        //     $data = array();
        //     $data['amount'] = $request->input('amount');
        //     $data['payment_method_id'] = $request->input('payment_method_id');
        //     return $data;
        // } catch (\Exception $e) {
        // }
    }
}

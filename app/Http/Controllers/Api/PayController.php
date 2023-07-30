<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Stripe\Webhook;
use Stripe\Customer;
use Stripe\Price;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Order;

class PayController extends Controller
{
    //
    public function checkout(Request $request) 
    {
        try {
            $user = $request->user();
            $token = $user->token;
            $courseId = $request->id;

            /*
                Stripe API key
            */
            Stripe::setApiKey('sk_test_51NZcPtCXCfIRAeZju3jeU4UtxGS5sAdaIWVefOnqzQFjSQ8tZ9nRUTxPisk5Nbgn5e5MkfZ3o7wbpf46tUHpucZt008grG1R37');

            $courseResult = Course::where('id', '=', $courseId)->first();
            //invalid request
            if(empty($courseResult)){
                return response()->json([
                    'code' => 400,
                    'msg' => "Course does not exist",
                    'data'=>''
                ], 400);
            }

            // order map variable for query
            $orderMap = [];
            $orderMap['course_id']= $courseId;
            $orderMap['user_token']=$token;
            $orderMap['status'] = 1;

            /*
                if the order has been placed before or not
                So we need Order model/table
            */

            $orderRes = Order::where($orderMap)->first();
            if(!empty($orderRes)){
                return response()->json([
                    'code' => 400,
                    'msg' => "You already bought this course",
                    'data' => ""
                ],);
            }

            // new order for the user and let's submit
            $YOUR_DOMAIN = env('APP_URL');
            $map = [];
            $map['user_token'] = $token;
            $map['course_id'] = $courseId;
            $map['total_amount'] = $courseResult->price;
            $map['status'] = 0;
            $map['created_at'] = Carbon::now();
            $orderNum = Order::insertGetId($map);
            
            // create payment session

            $checkOutSession = Session::create(
                [
                    'line_items'=>[[
                        'price_data'=>[
                            'currency'=>'USD',
                            'product_data'=>[
                                'name'=>$courseResult->name,
                                'description'=>$courseResult->description,
                            ],
                            'unit_amount'=>intval(($courseResult->price)*100),
                        ],
                        'quantity'=>1,
                    ]],
                    'payment_intent_data'=>[
                        'metadata'=>['order_num'=>$orderNum, 'user_token'=>$token],
                    ],
                    'metadata' => ['order_num' => $orderNum, 'user_token' => $token],
                    'mode'=>'payment',
                    'success_url'=> $YOUR_DOMAIN . 'success',
                    'cancel_url'=> $YOUR_DOMAIN . 'cancel'
                ]
            );

            return response()->json([
                'code' => 200,
                'msg' => "Success",
                'data'=>''
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Support\Str;
use App\Order;

use GuzzleHttp\Client;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();

        $response = [
            'message' => 'List of orders',
            'data' => $orders
        ];
        
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $newOrder = new Order;
        
        $newOrder->ORDER_CODE = strtoupper(Str::random(6));
        $newOrder->CUSTOMER_NAME = $request->customer_name;
        $newOrder->CUSTOMER_EMAIL = $request->customer_email;
        $newOrder->CUSTOMER_MOBILE = $request->customer_mobile;
        $newOrder->STATUS = 'CREATED';

        $result = $newOrder->saveOrFail();
        
        if ($result) {
            $proccesPaymentData = $this->createOrderRequest($newOrder->getAttributes());

            $response = [
                'message' => 'ORDER SUCCESSFULLY CREATED',
                'orderData' => $newOrder,
                'proccesPaymentData' => $proccesPaymentData
            ];
        } 

        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);

        $order->status = $request->status;

       $result = $order->saveOrFail();

       if ($result) {
        $response = [
            'message' => 'ORDER SUCCESSFULLY UPDATED',
            'orderData' => $order->getAttributes(),
        ];
    } 

    return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function authDataGenerator () {
        date_default_timezone_set('America/Bogota');

        $secretKey = "024h1IlD";
        $seed = date('c');
        $nonce = mt_rand();
        $nonceBase64 = base64_encode($nonce);

        $tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));

        return [
            'secretKey' => $secretKey,
            'tranKey' => $tranKey,
            'nonce' => $nonce,
            'nonceBase64' => $nonceBase64,
            'seed' => $seed 
        ];
    }

    public function createOrderRequest ($orderData) {
      
        $authData = $this->authDataGenerator();

        $requestBody = [
            'auth' => [
                'login' => '6dd490faf9cb87a9862245da41170ff2',
                'tranKey' => $authData['tranKey'],
                'nonce' => $authData['nonceBase64'],
                'seed' => $authData['seed'] 
            ],
            'buyer' => [
                'name' => $orderData['CUSTOMER_NAME'],
                'email' => $orderData['CUSTOMER_EMAIL'],
                'mobile' => $orderData['CUSTOMER_MOBILE']
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => 'http://localhost:4200/order-detail',
            'ipAddress' => '190.85.82.130',
            'userAgent' => 'PlacetoPay Sandbox',
            'payment' => [
                'reference' => $orderData['ORDER_CODE'],
                'description' => 'order payment '.strtoupper($orderData['ORDER_CODE']),
                'amount' => [
                    'currency' => 'COP',
                    'total' => '25000'
                ],
            'allowPartial' => false

            ]

        ];

        $client = new Client(['verify' => false ]);
        $response = $client->request('POST','https://test.placetopay.com/redirection/api/session/',
             ['json' => $requestBody]);

        $responseBody = json_decode($response->getBody()->getContents());

        if ($responseBody->status->status == 'OK') {
            return [
                'requestId' => $responseBody->requestId,
                'processUrl' => $responseBody->processUrl 
            ]; 
        } else {
            return false;
        }
    }

    public function getOrderStatus ($id) {

        $authData = $this->authDataGenerator();

        $requestBody = [
            'auth' => [
                'login' => '6dd490faf9cb87a9862245da41170ff2',
                'tranKey' => $authData['tranKey'],
                'nonce' => $authData['nonceBase64'],
                'seed' => $authData['seed'] 
            ]
        ];

        $client = new Client(['verify' => false ]);
        $response = $client->request('POST','https://test.placetopay.com/redirection/api/session/'.$id,
             ['json' => $requestBody]);

        $responseBody = json_decode($response->getBody()->getContents());

            return response()->json( [
                'orderStatusData' => $responseBody->status->status 
            ] , 200);
            
        
    }
}

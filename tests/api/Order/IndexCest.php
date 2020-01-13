<?php 

namespace Order;

use ApiTester;
use App\Order;

class IndexCest
{
    public function tryToTest(ApiTester $I)
    {
        // Arrange
        $orders = $I->haveMultiple(Order::class, 2);
        $expectedArray = [
            'data' => [
                $orders[0]->id => [
                    Order::ORDER_CODE => $orders[0]->order_code,
                    Order::CUSTOMER_NAME => $orders[0]->customer_name
                ],
                $orders[1]->id => [
                    Order::ORDER_CODE => $orders[1]->order_code,
                    Order::CUSTOMER_NAME => $orders[1]->customer_name
                ]
            ]
        ];

        $expectedJson = json_encode($expectedArray);
        //Act
        $I->sendGET('order');
        $response = $I->grabResponse();
        
        //Assert
        $I->seeResponseCodeIs(200);
        $I->assertEquals($expectedJson, $response);
    }
}

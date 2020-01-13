<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = "ois_order";

    const ORDER_CODE = 'order_code';
    const CUSTOMER_NAME = 'customer_name';
    const CUSTOMER_EMAIL = 'customer_email';
    const CUSTOMER_MOBILE = 'customer_mobile';
    const STATUS = 'status';
}

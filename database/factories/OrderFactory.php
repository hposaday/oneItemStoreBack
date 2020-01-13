<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'order_code' => (string) $faker->randomNumber(6),
        'customer_name' => $faker->name(),
        'customer_email' => $faker->freeEmail(),
        'customer_mobile' => (string) $faker->randomNumber(6),
        'status' => $faker->randomElement([
            'CREATED',
            'PAYED',
            'REJECTED'
        ])
    ];
});

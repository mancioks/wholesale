<?php

use App\Models\Order;
use App\Models\Product;

return [
    'PVM' => 21,
    'ORDER_TYPE_NORMAL' => Order::ORDER_TYPE_NORMAL,
    'ORDER_TYPE_ISSUE' => Order::ORDER_TYPE_ISSUE,
    'PRODUCT_TYPE_REGULAR' => Product::PRODUCT_TYPE_REGULAR,
    'PRODUCT_TYPE_PERSONALIZED' => Product::PRODUCT_TYPE_PERSONALIZED,
];

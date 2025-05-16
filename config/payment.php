<?php

return [
    'default' => env('PAYMENT_PROVIDER', 'cash'),
    'providers' => ['stripe', 'paypal', 'cash']
];

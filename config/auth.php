<?php

return [

    'defaults' => [
        'guard' => 'employee',
        'passwords' => 'employees',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'employees', // Use employees provider for the web guard
        ],

        'employee' => [
            'driver' => 'session',
            'provider' => 'employees',
        ],
    ],

    'providers' => [
        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class,
        ],
    ],

    'passwords' => [
        'employees' => [
            'provider' => 'employees',
            'table' => 'password_reset_tokens', // Ensure this table exists in your DB
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];

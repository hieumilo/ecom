<?php

return [
    'message' => [
        'success' => [
            'user' => [
                'create' => 'user created successfuly',
                'update' => 'user updated successfuly',
                'delete' => 'user deleted successfuly',
            ],
            'category' => [
                'create' => 'category created successfuly',
                'update' => 'category updated successfuly',
                'delete' => 'category deleted successfuly',
            ],
            'product' => [
                'create' => 'product created successfuly',
                'update' => 'product updated successfuly',
                'delete' => 'product deleted successfuly',
            ],
            'suggest' => [
                'create' => 'suggest created successfuly',
                'update' => 'suggest updated successfuly',
                'delete' => 'suggest deleted successfuly',
            ],
            'order' => [
                'create' => 'order created successfuly',
                'update' => 'order updated successfuly',
                'delete' => 'order deleted successfuly',
            ],
        ],
        'fails' => [
            'login' => [
                'invalid_credentials' => 'email or password failed',
                'could_not_create_token' => 'could not create token',
                'not_administrator' => 'not a administrator',
            ],
            'me' => [
                'not_found' => 'user not found',
                'token_expired' => 'token expired',
                'token_invalid' => 'token invalid',
                'token_absent' => 'token absent',
            ],
            'category' => [
                'parent' => 'can not be its own child',
                'update' => 'category can not update',
                'create' => 'category can not create',
                'delete' => 'category can not delete',
            ],
            'product' => [
                'update' => 'product can not update',
                'create' => 'product can not create',
                'delete' => 'product can not delete',
            ],
            'order' => [
                'update' => 'order can not update',
                'create' => 'order can not create',
                'delete' => 'order can not delete',
                'stock' => 'Quantity exceeds the inventory',
            ],
        ],
    ],
];
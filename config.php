<?php

return [
    'db' => [
        'host' => getenv('DB_HOST'),
        'name' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD')
    ],

    'routes' => [
        [
            'from' => 'ALA',
            'to' => 'TSE'
        ],
        [
            'from' => 'TSE',
            'to' => 'ALA'
        ],
        [
            'from' => 'ALA',
            'to' => 'MOW',
        ],
        [
            'from' => 'MOW',
            'to' => 'ALA'
        ],
        [
            'from' => 'ALA',
            'to' => 'CIT'
        ],
        [
            'from' => 'CIT',
            'to' => 'ALA'
        ],
        [
            'from' => 'TSE',
            'to' => 'MOW'
        ],
        [
            'from' => 'MOW',
            'to' => 'TSE'
        ],
        [
            'from' => 'TSE',
            'to' => 'LED'
        ],
        [
            'from' => 'LED',
            'to' => 'TSE'
        ]
    ]
];

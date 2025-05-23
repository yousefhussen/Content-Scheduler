<?php

return [
    'name' => 'ActivityLog',
    'loggable_actions' => [
        'POST' => [
            'posts' => 'Added Post',

        ],
        'PUT' => [
            'posts/{id}' => 'Updated Post',

        ],
        'DELETE' => [
            'posts/{id}' => 'Deleted Post',

        ],
    ],
];

<?php

return [
    'name' => 'ActivityLog',
    'loggable_actions' => [
        'POST' => [
            'posts' => 'Added Post',
            'posts/{post}' => 'Updated Post',
            'platforms/SetStatus' => 'Set Platform Status',
        ],
        'PUT' => [
            'posts/{post}/publish' => 'Published Post',
        ],
        'DELETE' => [
            'posts/{post}' => 'Deleted Post',
        ],
        'GET' => [
            'posts/scheduled' => 'Viewed Scheduled Posts',
            'platforms' => 'Viewed Platforms',
            'platforms/user' => 'Viewed User Platforms',
        ],
    ],

];

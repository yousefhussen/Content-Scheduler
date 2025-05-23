<?php

return [
    'name' => 'Post',

    'facebook' => [
    'text' => [
        'character_limit' => 63206,
    ],
    'images' => [
        'max_count' => 80,
        'max_size_mb' => 4,
        'aspect_ratio_restrictions' => false,
    ],
    'videos' => [
        'max_count' => 1,
        'max_size_mb' => 100,
        'aspect_ratio_restrictions' => false,
        'max_duration_hours' => 4,
    ],
    'gifs' => [
        'max_count' => 1,
        'max_size_mb' => 100,
        'aspect_ratio_restrictions' => false,
    ],
    'posts_per_24_hours' => 25,
],

    'instagram' => [
    'text' => [
        'character_limit' => 2200,
    ],
    'images' => [
        'max_count' => 10,
        'max_size_mb' => 8,
        'aspect_ratio_range' => ['min' => '4:5', 'max' => '1.91:1'],
        'min_width' => 320,
        'max_width' => 1440,
    ],
    'videos' => [
        'max_count' => 1,
        'max_size_mb' => 100,
        'aspect_ratio' => ['4:5', '16:9'],
        'max_duration_seconds' => 60,
    ],
    'gifs' => [
        'max_count' => 1,
        'max_size_mb' => 100,
        'aspect_ratio' => ['4:5', '16:9'],
    ],
    'posts_per_24_hours' => 25,
],

    'linkedin' => [
    'text' => [
        'character_limit' => 1300,
    ],
    'images' => [
        'max_count' => 9,
        'max_size_mb' => 10,
        'aspect_ratio_restrictions' => false,
    ],
    'videos' => [
        'max_count' => 1,
        'max_size_mb' => 100,
        'aspect_ratio_restrictions' => false,
        'max_duration_minutes' => 30,
    ],
    'gifs' => [
        'max_count' => 1,
        'max_size_mb' => 100,
        'aspect_ratio_restrictions' => false,
    ],
    'posts_per_24_hours' => 50,
],

    'twitter' => [
    'text' => [
        'character_limit' => 280,
    ],
    'images' => [
        'max_count' => 4,
        'max_size_mb' => 5,
        'aspect_ratio_restrictions' => false,
    ],
    'videos' => [
        'max_count' => 1,
        'max_size_mb' => 100,
        'aspect_ratio_range' => ['min' => '32x32', 'max' => '1280x1024'],
        'max_duration_seconds' => 140,
    ],
    'gifs' => [
        'max_count' => 1,
        'max_size_mb' => 15,
        'max_dimensions' => ['width' => 1280, 'height' => 1080],
    ],
    'posts_per_24_hours' => 100,
],

];

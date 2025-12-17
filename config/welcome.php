<?php

return [
    'name' => 'Menu Ginebro',
    'description' => 'Order your meals easily and quickly!',
    'focus_url' => '/admin',
    'documentation_url' => '', // Optional URL to documentation
    'primary_color' => '#009ca6',
    'accent_color' => '#2b8561ff',
    'footer_text' => '© ' . date('Y') . ' Duckhats. All rights reserved.',
    'AdminUser' => [
        'name' => 'Admin',
        'last_name' => 'Ginebro',
        'email' => 'admin@ginebro.cat',
        'password' => 'Password123',
        'user_type_id' => 1,
    ],
    'kitchenUser' => [
        'name' => 'Cuina',
        'last_name' => 'Ginebro',
        'email' => 'cuina@ginebro.cat',
        'password' => 'Password123',
        'user_type_id' => 3,
    ],
];

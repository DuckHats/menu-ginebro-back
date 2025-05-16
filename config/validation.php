<?php

// Path: config/validation.php
// Validation rules for all the api endpoints

use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Week;

return [
    'auth' => [
        'login' => [
            'user' => 'required',
            'password' => 'required',
        ],
        'register' => [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            // Modificar especificacions de password
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ],
        'reset_password' => [
            'email' => 'required|email|exists:users,email',
            // Modificar especificacions de password
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ],
        'send_reset_code' => [
            'email' => 'required|email|exists:users,email',
        ],
        'send_email_verification_code' => [
            'email' => 'required|email|exists:users,email',
        ],
        'verify_email' => [
            'email' => 'required|email|exists:users,email',
            'verification_code' => 'required|integer',
        ],
    ],
    'users' => [
        'store' => [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            // Modificar especificacions de password
            'password' => 'required|string|min:8',
        ],
        'update' => [
            'name' => 'required|string|max:255|unique:users,name,{id}',
            'email' => 'required|string|email|max:255|unique:users,email,{id}',
            // Modificar especificacions de password
            'password' => 'nullable|string|min:8',
        ],
        'patch' => [
            'name' => 'nullable|string|max:255|unique:users,name,{id}',
            'email' => 'nullable|string|email|max:255|unique:users,email,{id}',
            // Modificar especificacions de password
            'password' => 'nullable|string|min:8',
        ],
        'unBan' => [
            'reason' => 'nullable|string',
        ],
        'avatar' => [
            'avatar' => 'required|string',
        ],
    ],

    'images' => [
        'store' => [
            'path' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ],
        'update' => [
            'path' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'month' => 'required|integer',
            'year' => 'required|integer',
        ],
    ],

    'menus' => [
        'store' => [
            'day' => 'required|date',
        ],
        'update' => [
            'day' => 'nullable|date',
        ],
        'patch' => [
            'day' => 'nullable|date',

        ],
    ],

    'dishes' => [
        'store' => [
            'menu_id' => 'required|integer|exists:menus,id',
            'dish_type_id' => 'required|integer|exists:dish_type,id',
            'options' => 'nullable|string',
        ],
        'update' => [
            'menu_id' => 'required|integer|exists:menus,id',
            'dish_type_id' => 'required|integer|exists:dish_type,id',
            'options' => 'nullable|string',
        ],
    ],
    'dish_types' => [
        'store' => [
            'name' => 'required|string|max:255',
        ],
        'update' => [
            'name' => 'nullable|string|max:255',
        ],
    ],
    'orders' => [
        'store' => [
            'user_id' => 'required|integer|exists:users,id',
            'order_date' => 'required|date',
            'allergies' => 'nullable|string|max:255',
            'order_type_id' => 'required|integer|exists:order_types,id',
            'order_status_id' => 'required|integer|exists:order_status,id',
        ],
        'update' => [
            'user_id' => 'required|integer|exists:users,id',
            'order_date' => 'nullable|date',
            'allergies' => 'nullable|string|max:255',
            'order_type_id' => 'required|integer|exists:order_types,id',
            'order_status_id' => 'required|integer|exists:order_status,id',

        ],
    ],
    'order_details' => [
        'store' => [
            'order_id' => 'required|integer|exists:orders,id',
            'dish_id' => 'required|integer|exists:dishes,id',
        ],
        'update' => [
            'order_id' => 'required|integer|exists:orders,id',
            'dish_id' => 'required|integer|exists:dishes,id',
        ],
    ],
    'order_status' => [
        'store' => [
            'name' => 'required|string|max:255',
        ],
        'update' => [
            'name' => 'required|string|max:255',
        ],
    ],


];
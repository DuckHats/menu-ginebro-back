<?php

// Path: config/validation.php
// Validation rules for all the api endpoints

return [
    'auth' => [
        'login' => [
            'user' => 'required',
            'password' => 'required',
        ],
        'register' => [
            'name' => 'required|string|max:255|unique:users',
            'last_name' => 'required|string|max:255',
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
        'send_register_code' => [
            'email' => 'required|email|unique:users',
        ],
        'complete_register' => [
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'verification_code' => 'required|integer',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ],

    ],
    'users' => [
        'store' => [
            'name' => 'required|string|max:255|unique:users',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // Modificar especificacions de password
            'password' => 'required|string|min:8',
        ],
        'update' => [
            'name' => 'required|string|max:255|unique:users,name,{id}',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,{id}',
            // Modificar especificacions de password
            'password' => 'nullable|string|min:8',
        ],
        'patch' => [
            'name' => 'nullable|string|max:255|unique:users,name,{id}',
            'last_name' => 'nullable|string|max:255',
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
            'order_date' => 'required|date',
            'order_type_id' => 'required|integer|exists:order_types,id',
            'order_status_id' => 'required|integer|exists:order_status,id',
            'allergies' => 'nullable|string|max:255',
            'has_tupper' => 'required|boolean',
            'option1' => 'nullable|string|max:255',
            'option2' => 'nullable|string|max:255',
            'option3' => 'nullable|string|max:255',
        ],
        'update' => [
            'order_date' => 'nullable|date',
            'order_type_id' => 'nullable|integer|exists:order_types,id',
            'order_status_id' => 'nullable|integer|exists:order_status,id',
            'allergies' => 'nullable|string|max:255',
            'has_tupper' => 'nullable|boolean',
            'option1' => 'nullable|string|max:255',
            'option2' => 'nullable|string|max:255',
            'option3' => 'nullable|string|max:255',

        ],

        'updateStatus' => [
            'order_status_id' => 'required|integer|exists:order_status,id',
        ],
        'getByDate' => [
            'date' => 'required|date',
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

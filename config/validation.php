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
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            // Modificar especificacions de password
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
            'phone' => 'nullable|string|max:20',
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
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            // Modificar especificacions de password
            'password' => 'required|string|min:8',
        ],
        'update' => [
            'username' => 'required|string|max:255|unique:users,username,{id}',
            'email' => 'required|string|email|max:255|unique:users,email,{id}',
            // Modificar especificacions de password
            'password' => 'nullable|string|min:8',
        ],
        'patch' => [
            'username' => 'nullable|string|max:255|unique:users,username,{id}',
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
        'bulkUsers' => [
            'users' => 'required|array',
            'users.*.username' => 'required|string|unique:users,username',
            'users.*.email' => 'required|email|unique:users,email',
            'users.*.status' => 'nullable|integer|in:0,1',
            'users.*.password' => 'required|string|min:6',
        ],
    ],
];
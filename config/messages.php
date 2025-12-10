<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Response Messages
    |--------------------------------------------------------------------------
    |
    | Centralized messages for API responses throughout the application.
    | This improves maintainability and makes it easier to update messages.
    |
    */

    'auth' => [
        // Success messages
        'register_success' => 'Registered successfully',
        'register_complete_success' => 'Registered successfully.',
        'login_success' => 'Login successful',
        'logout_success' => 'Logged out successfully',
        'reset_code_sent' => 'The reset code has been sent to your email.',
        'password_reset_success' => 'Password reset successfully.',
        'verification_code_sent' => 'The verification code has been sent to your email.',
        'email_verified_success' => 'Email verified successfully.',
        'register_code_sent' => 'Verification code sent.',

        // Error messages
        'invalid_credentials' => 'The provided credentials are incorrect.',
        'account_inactive' => 'Your account is banned or inactive.',
        'invalid_or_expired_code' => 'Invalid or expired code.',
        'code_expired' => 'The code has expired.',
        'email_already_registered' => 'This email is already registered.',
        'invalid_verification_code' => 'Invalid or expired verification code.',
        'user_already_exists' => 'User already exists.',
    ],

    'users' => [
        // Success messages
        'list_retrieved' => 'List of users retrieved successfully.',
        'created_success' => 'User created successfully.',
        'retrieved_success' => 'User retrieved successfully.',
        'updated_success' => 'User updated successfully.',
        'deleted_success' => 'User deleted successfully.',
        'avatar_updated' => 'Avatar updated successfully.',
        'disabled_success' => 'User disabled successfully.',
        'enabled_success' => 'User enabled successfully.',
        'is_admin' => 'User is admin.',
        'is_not_admin' => 'User is not admin.',

        // Error messages
        'not_found' => 'User not found.',
    ],

    'menus' => [
        // Success messages
        'list_retrieved' => 'List of menus retrieved successfully.',
        'created_success' => 'Menu created successfully.',
        'retrieved_success' => 'Menu retrieved successfully.',
        'updated_success' => 'Menu updated successfully.',
        'deleted_success' => 'Menu deleted successfully.',
        'disabled_success' => 'Menu disabled successfully.',
        'enabled_success' => 'Menu enabled successfully.',

        // Error messages
        'not_found' => 'Menu not found.',
    ],

    'dishes' => [
        // Success messages
        'list_retrieved' => 'List of dishes retrieved successfully.',
        'created_success' => 'Dish created successfully.',
        'retrieved_success' => 'Dish retrieved successfully.',
        'updated_success' => 'Dish updated successfully.',
        'deleted_success' => 'Dish deleted successfully.',

        // Error messages
        'not_found' => 'Dish not found.',
    ],

    'orders' => [
        // Success messages
        'list_retrieved' => 'List of orders retrieved successfully.',
        'created_success' => 'Order created successfully.',
        'retrieved_success' => 'Order retrieved successfully.',
        'updated_success' => 'Order updated successfully.',
        'deleted_success' => 'Order deleted successfully.',
        'status_updated' => 'Order status updated successfully.',

        // Error messages
        'not_found' => 'Order not found.',
    ],

    'order_details' => [
        // Success messages
        'list_retrieved' => 'List of order details retrieved successfully.',
        'created_success' => 'Order detail created successfully.',
        'retrieved_success' => 'Order detail retrieved successfully.',
        'updated_success' => 'Order detail updated successfully.',
        'deleted_success' => 'Order detail deleted successfully.',

        // Error messages
        'not_found' => 'Order detail not found.',
    ],

    'allergies' => [
        // Success messages
        'list_retrieved' => 'List of allergies retrieved successfully.',
        'created_success' => 'Allergy created successfully.',
        'retrieved_success' => 'Allergy retrieved successfully.',
        'updated_success' => 'Allergy updated successfully.',
        'deleted_success' => 'Allergy deleted successfully.',

        // Error messages
        'not_found' => 'Allergy not found.',
    ],

    'generic' => [
        // Success messages
        'operation_success' => 'Operation completed successfully.',
        'creation_in_progress' => 'Creation in progress.',

        // Error messages
        'invalid_parameters' => 'Invalid parameters provided.',
        'validation_error' => 'Validation error.',
        'not_found' => 'Resource not found.',
        'unauthorized' => 'Unauthorized access.',
        'forbidden' => 'Access forbidden.',
        'internal_error' => 'An internal error occurred.',
    ],

    'errors' => [
        'user_not_found' => 'User not found.',
        'fetch_failed' => 'Error while retrieving data.',
        'create_failed' => 'Error while creating resource.',
        'update_failed' => 'Error while updating resource.',
        'delete_failed' => 'Error while deleting resource.',
        'operation_failed' => 'Error while performing operation.',
        'registration_failed' => 'Error while creating user',
        'login_failed' => 'Error during login',
        'logout_failed' => 'Error while logging out',
        'send_reset_code_failed' => 'Error while sending reset code',
        'reset_password_failed' => 'Error while resetting password',
        'send_verification_code_failed' => 'Error while sending verification code',
        'verify_email_failed' => 'Error while verifying email',
        'send_register_code_failed' => 'Error sending verification code.',
        'complete_register_failed' => 'Error during registration.',
        'banning_user_failed' => 'Error while banning user.',
        'checking_admin_failed' => 'Error while cheking if user is admin.',
    ],
];

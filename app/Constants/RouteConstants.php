<?php

namespace App\Constants;

class RouteConstants
{
    // Auth Routes
    const SEND_REGISTER_CODE = '/register/send-code';

    const COMPLETE_REGISTER = '/register/complete';

    const REGISTER = '/register';

    const LOGIN = '/login';

    const LOGOUT = '/logout';

    const LOGOUT_ALL_SESSIONS = '/logoutAllSessions';

    const FORGOT_PASSWORD = '/forgot-password';

    const RESET_PASSWORD = '/reset-password';

    const VERIFY_EMAIL = '/verify/email';

    const VERIFY_EMAIL_CONFIRM = '/verify/email/confirm';

    // User Routes
    const USERS = '/users';

    const USERS_EXPORT = '/users/export';

    const USERS_IMPORT = '/users/import';

    const USERS_ME = '/users/me';

    const USERS_DETAIL = '/users/{id}';

    const USERS_CREATE = '/users';

    const USERS_UPDATE = '/users/{id}';

    const USERS_PATCH = '/users/{id}';

    const USERS_DESTROY = '/users/{id}';

    const USERS_AVATAR = '/users/{id}/avatar';

    const USERS_DISABLE = '/users/{id}/disable';

    const USERS_ENABLE = '/users/{id}/enable';

    const USERS_ADMIN_CHECK = '/users/is_admin';

    // Menu Routes
    const MENUS = '/menus';

    const MENUS_EXPORT = '/menus/export';

    const MENUS_IMPORT = '/menus/import';

    const MENUS_DETAIL = '/menus/{day}';

    const MENUS_CREATE = '/menus';

    const MENUS_UPDATE = '/menus/{id}';

    const MENUS_PATCH = '/menus/{id}';

    const MENUS_DESTROY = '/menus/{id}';

    const MENUS_DISABLE = '/menus/disable';

    const MENUS_ENABLE = '/menus/enable';

    // Dish Routes
    const DISHES = '/dishes';

    const DISHES_EXPORT = '/dishes/export';

    const DISHES_DETAIL = '/dishes/{id}';

    const DISHES_CREATE = '/dishes';

    const DISHES_UPDATE = '/dishes/{id}';

    const DISHES_PATCH = '/dishes/{id}';

    const DISHES_DESTROY = '/dishes/{id}';

    // Order Routes
    const ORDERS = '/orders';

    const ORDERS_BY_DATE = '/orders_by_date/{date}';

    const ORDERS_BY_USER = '/orders_by_user/{userId}';

    const ORDERS_CHECK_DATE = '/orders/checkDate/{date}';

    const ORDERS_EXPORT = '/orders/export';

    const ORDERS_DETAIL = '/orders/{id}';

    const ORDERS_CREATE = '/orders';

    const ORDERS_UPDATE = '/orders/{id}';

    const ORDERS_PATCH = '/orders/{id}';

    const ORDERS_DESTROY = '/orders/{id}';

    const ORDERS_UPDATE_STATUS = '/orders/updateStatus/{id}';

    // Order Status
    const ORDER_STATUS = '/orders_status';

    // Order Type
    const ORDER_TYPE = '/orders_type';

    // Menu Days Routes
    const MENU_DAYS = '/menu_days';

    const MENU_DAYS_EXPORT = '/menu_days/export';

    const MENU_DAYS_DETAIL = '/menu_days/{id}';

    const MENU_DAYS_CREATE = '/menu_days';

    const MENU_DAYS_UPDATE = '/menu_days/{id}';

    const MENU_DAYS_PATCH = '/menu_days/{id}';

    const MENU_DAYS_DESTROY = '/menu_days/{id}';

    // Order Details Routes
    const ORDER_DETAILS = '/order_details';

    const ORDER_DETAILS_EXPORT = '/order_details/export';

    const ORDER_DETAILS_DETAIL = '/order_details/{id}';

    const ORDER_DETAILS_CREATE = '/order_details';

    const ORDER_DETAILS_UPDATE = '/order_details/{id}';

    const ORDER_DETAILS_PATCH = '/order_details/{id}';

    const ORDER_DETAILS_DESTROY = '/order_details/{id}';

    // Allergy Routes
    const ALLERGIES = '/allergies';
}

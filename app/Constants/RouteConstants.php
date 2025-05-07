<?php

namespace App\Constants;

class RouteConstants
{
    // Auth Routes
    const REGISTER = '/register';

    const LOGIN = '/login';

    const LOGOUT = '/logout';

    const LOGOUT_ALL_SESSIONS = '/logoutAllSessions';

    const FORGOT_PASSWORD = '/forgot-password';

    const RESET_PASSWORD = '/reset-password';

    const VERIFY_EMAIL = '/verify/email';

    const VERIFY_EMAIL_CONFIRM = '/verify/email/confirm';

    const VERIFY_PHONE = '/verify/phone';

    const VERIFY_PHONE_CONFIRM = '/verify/phone/confirm';

    // User Routes
    const USERS = '/users';

    const USERS_EXPORT = '/users/export';

    const USERS_ME = '/users/me';

    const USERS_DETAIL = '/users/{id}';

    const USERS_CREATE = '/users';

    const USERS_UPDATE = '/users/{id}';

    const USERS_PATCH = '/users/{id}';

    const USERS_DESTROY = '/users/{id}';

    const USERS_AVATAR = '/users/{id}/avatar';

    const USERS_DISABLE = '/users/{id}/disable';

    const USERS_ENABLE = '/users/{id}/enable';

    const USERS_BULK = '/users/bulk';

    const USERS_ADMIN_CHECK = '/users/is_admin';

    // Error Routes
    const ERRORS = '/errors';

    const ERROR_DETAIL = '/errors/{id}';

    const ERROR_CREATE = '/errors';

    const ERROR_UPDATE = '/errors/{id}';

    const ERROR_PATCH = '/errors/{id}';

    const ERROR_DELETE = '/errors/{id}';

    // Post Routes
    const POSTS = '/posts';

    const POST_DETAIL = '/posts/{id}';

    const POST_CREATE = '/posts';

    const POST_UPADTE = '/posts/{id}';

    const POST_PATCH = '/posts/{id}';

    const POST_DELETE = '/posts/{id}';

    // User Settings Routes
    const USER_SETTINGS = '/usettings';

    const USER_SETTING_DETAIL = '/usettings/{id}';

    const USER_SETTING_CREATE = '/usettings';

    const USER_SETTING_UPDATE = '/usettings/{id}';

    const USER_SETTING_PATCH = '/usettings/{id}';

    const USER_SETTING_DELETE = '/usettings/{id}';

    // Childs Routes
    const CHILDS = '/childs';

    const CHILDS_EXPORT = '/childs/export';

    const CHILD_DETAIL = '/childs/{id}';

    const CHILD_INSPECT = '/childs/{id}/inspect';

    const CHILD_MULTIPLE_INSPECT = '/childs/bulk/inspect';

    const CHILD_CREATE = '/childs';

    const CHILD_UPDATE = '/childs/{id}';

    const CHILD_PATCH = '/childs/{id}';

    const CHILD_UPLOAD_IMAGE = '/childs/{id}/upload-image';

    const CHILD_DELETE = '/childs/{id}';

    // Policy Routes
    const USER_POLICY = '/upolicy';

    const USER_POLICY_DETAIL = '/upolicy/{id}';

    const USER_POLICY_CREATE = '/upolicy';

    const USER_POLICY_UPDATE = '/upolicy/{id}';

    const USER_POLICY_PATCH = '/upolicy/{id}';

    const USER_POLICY_DELETE = '/upolicy/{id}';

    // Notification Routes
    const USER_NOTIFICATION = '/unotification';

    const USER_NOTIFICATION_DETAIL = '/unotification/{id}';

    const USER_NOTIFICATION_CREATE = '/unotification';

    const USER_NOTIFICATION_UPDATE = '/unotification/{id}';

    const USER_NOTIFICATION_PATCH = '/unotification/{id}';

    const USER_NOTIFICATION_DELETE = '/unotification/{id}';

    // Group Routes
    const GROUPS = '/groups';

    const GROUPS_EXPORT = '/groups/export';

    const GROUP_DETAIL = '/groups/{id}';

    const GROUP_CREATE = '/groups';

    const GROUP_BULK = '/groups/bulk';

    const GROUP_UPDATE = '/groups/{id}';

    const GROUP_PATCH = '/groups/{id}';

    const GROUP_UPLOAD_IMAGE = '/groups/{id}/upload-image';

    const GROUP_DELETE = '/groups/{id}';

    // Activity Routes
    const ACTIVITIES = '/activities';

    const ACTIVITIES_EXPORT = '/activities/export';

    const ACTIVITY_DETAIL = '/activities/{id}';

    const ACTIVITY_CREATE = '/activities';

    const ACTIVITY_BULK = '/activities/bulk';

    const ACTIVITY_UPDATE = '/activities/{id}';

    const ACTIVITY_PATCH = '/activities/{id}';

    const ACTIVITY_UPLOAD_IMAGE = '/activities/{id}/upload-image';

    const ACTIVITY_DELETE = '/activities/{id}';

    // Monitor Routes
    const MONITORS = '/monitors';

    const MONITORS_EXPORT = '/monitors/export';

    const MONITOR_DETAIL = '/monitors/{id}';

    const MONITOR_CREATE = '/monitors';

    const MONITOR_BULK = '/monitors/bulk';

    const MONITOR_UPDATE = '/monitors/{id}';

    const MONITOR_PATCH = '/monitors/{id}';

    const MONITOR_UPLOAD_IMAGE = '/monitors/{id}/upload-image';

    const MONITOR_DELETE = '/monitors/{id}';

    // Photo Routes
    const PHOTOS = '/photos';

    const PHOTOS_EXPORT = '/photos/export';

    const PHOTO_DETAIL = '/photos/{id}';

    const PHOTO_CREATE = '/photos';

    const PHOTO_UPDATE = '/photos/{id}';

    const PHOTO_PATCH = '/photos/{id}';

    const PHOTO_DELETE = '/photos/{id}';

    // Scheduled Activity Routes

    const SCHEDULED_ACTIVITIES = '/scheduled-activities';

    const SCHEDULED_ACTIVITIES_EXPORT = '/scheduled-activities/export';

    const SCHEDULED_ACTIVITY_DETAIL = '/scheduled-activities/{id}';

    const SCHEDULED_ACTIVITY_CREATE = '/scheduled-activities';

    const SCHEDULED_ACTIVITY_BULK = '/scheduled-activities/bulk';

    const SCHEDULED_ACTIVITY_UPDATE = '/scheduled-activities/{id}';

    const SCHEDULED_ACTIVITY_PATCH = '/scheduled-activities/{id}';

    const SCHEDULED_ACTIVITY_DELETE = '/scheduled-activities/{id}';
}

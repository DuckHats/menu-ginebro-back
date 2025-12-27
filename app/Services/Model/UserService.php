<?php

namespace App\Services\Model;

use App\Constants\ErrorCodes;
use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserService
{
    public function getUsers(Request $request)
    {
        try {
            Gate::authorize('viewAll', $request->user());

            $query = User::query();

            // Apply Search
            if ($request->has('search') && ! empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Apply Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $allowedColumns = ['id', 'name', 'last_name', 'email', 'balance', 'status', 'created_at'];

            if (in_array($sortBy, $allowedColumns)) {
                $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Apply Pagination
            $perPage = $request->get('per_page', 15);
            $users = $query->paginate($perPage);

            return UserResource::collection($users)->additional([
                'status' => 'success',
                'message' => config('messages.users.list_retrieved'),
                'code' => ApiResponse::OK_STATUS,
            ]);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::FETCH_FAILED,
                config('messages.errors.fetch_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function createUser(Request $request)
    {
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error(
                ErrorCodes::VALIDATION_ERROR,
                config('messages.generic.validation_error'),
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = new User($validatedData['data']);
            $user->save();

            return ApiResponse::success(new UserResource($user), config('messages.users.created_success'), ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::CREATE_FAILED,
                config('messages.errors.create_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function getUserById(Request $request, $id)
    {
        try {
            Gate::authorize('view', $request->user());
            $user = User::where('id', $id)->with('allergies');
            // Uncomment the following line if you want to apply relations
            // $this->applyRelations($user, $request);

            $user = $user->first();
            if (! $user) {
                return ApiResponse::error(
                    ErrorCodes::NOT_FOUND,
                    config('messages.users.not_found'),
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $userResource = new UserResource($user);

            return ApiResponse::success($userResource, config('messages.users.retrieved_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::FETCH_FAILED,
                config('messages.errors.fetch_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function updateUser(Request $request, $id)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'update', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                ErrorCodes::VALIDATION_ERROR,
                config('messages.generic.validation_error'),
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    ErrorCodes::NOT_FOUND,
                    config('messages.users.not_found'),
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('update', $user);

            $user->update($validatedData['data']);

            if (isset($validatedData['data']['allergies'])) {
                $user->allergies()->sync($validatedData['data']['allergies']);
            }

            return ApiResponse::success(new UserResource($user), config('messages.users.updated_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::UPDATE_FAILED,
                config('messages.errors.update_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function patchUser(Request $request, $id)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'patch', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                ErrorCodes::VALIDATION_ERROR,
                config('messages.generic.validation_error'),
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    ErrorCodes::NOT_FOUND,
                    config('messages.users.not_found'),
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('update', $user);

            $user->update($validatedData['data']);

            return ApiResponse::success(new UserResource($user), config('messages.users.updated_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::UPDATE_FAILED,
                config('messages.errors.update_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    ErrorCodes::NOT_FOUND,
                    config('messages.users.not_found'),
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('delete', $user);

            $user->delete();

            return ApiResponse::success([], config('messages.users.deleted_success'), ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::DELETE_FAILED,
                config('messages.errors.delete_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function updateAvatar(Request $request, $id, $authUser)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'avatar', $placeholders);

        if (! $validatedData['success']) {
            return ApiResponse::error(
                ErrorCodes::VALIDATION_ERROR,
                config('messages.generic.validation_error'),
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    ErrorCodes::NOT_FOUND,
                    config('messages.users.not_found'),
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $user->profile_picture_url = $validatedData['data']['avatar'];
            $user->save();

            return ApiResponse::success(new UserResource($user), config('messages.users.avatar_updated'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::UPDATE_FAILED,
                config('messages.errors.update_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function disableUser(Request $request, $id)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'disableUser', $placeholders);
        if (! $validatedData['success']) {
            return ApiResponse::error(
                ErrorCodes::VALIDATION_ERROR,
                config('messages.generic.validation_error'),
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $requestUser = $request->user();

            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    ErrorCodes::NOT_FOUND,
                    config('messages.users.not_found'),
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('disable', $user, $requestUser);
            $user->status = User::STATUS_INACTIVE;
            $user->save();

            return ApiResponse::success(new UserResource($user), config('messages.users.disabled_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::UPDATE_FAILED,
                config('messages.errors.banning_user_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function enableUser(Request $request, $id)
    {
        $placeholders = ['id' => $id];
        $validatedData = ValidationHelper::validateRequest($request, 'users', 'enableUser', $placeholders);
        if (! $validatedData['success']) {
            return ApiResponse::error(
                ErrorCodes::VALIDATION_ERROR,
                config('messages.generic.validation_error'),
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $requestUser = $request->user();

            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    ErrorCodes::NOT_FOUND,
                    config('messages.users.not_found'),
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('disable', $user, $requestUser);
            $user->status = User::STATUS_ACTIVE;
            $user->save();

            return ApiResponse::success(new UserResource($user), config('messages.users.enabled_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::UPDATE_FAILED,
                config('messages.errors.banning_user_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    // Uncomment this method if you want to implement bulk user creation
    // public function bulkUsers(Request $request)
    // {

    //     $validatedData = ValidationHelper::validateRequest($request, 'users', 'bulkUsers');

    //     if (! $validatedData['success']) {
    //         return ApiResponse::error(
    //             'VALIDATION_ERROR',
    //             'Invalid parameters provided.',
    //             $validatedData['errors'],
    //             ApiResponse::INVALID_PARAMETERS_STATUS
    //         );
    //     }

    //     BulkUserCreationJob::dispatch($request->input('users'))->onQueue('bulk-processing');

    //     return ApiResponse::success([], 'Creation in progress.', ApiResponse::ACCEPTED_STATUS);
    // }

    public function isAdmin()
    {
        try {
            $requestUser = Auth::user();
            $user = User::find($requestUser->id);
            if (! $user) {
                return ApiResponse::error(
                    ErrorCodes::NOT_FOUND,
                    config('messages.users.not_found'),
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            if ($user->isAdmin() || $user->isCook()) {
                return ApiResponse::success(['admin' => true], config('messages.users.is_admin'), ApiResponse::OK_STATUS);
            } else {
                return ApiResponse::success(['admin' => false], config('messages.users.is_not_admin'), ApiResponse::OK_STATUS);
            }
        } catch (\Throwable $e) {
            return ApiResponse::error(
                ErrorCodes::FUNCTION_FAILED,
                config('messages.errors.checking_admin_failed'),
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    // Uncomment this method if you want to implement relations
    // private function applyRelations($query, Request $request)
    // {
    //     if ($request->get('with_user_settings')) {
    //         $query->with('settings');
    //     }

    //     if ($request->get('with_user_notifications')) {
    //         $query->with('notifications');
    //     }

    //     if ($request->get('with_policies')) {
    //         $query->with('policies');
    //     }
    // }
}

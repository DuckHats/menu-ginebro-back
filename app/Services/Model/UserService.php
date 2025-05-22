<?php

namespace App\Services\Model;

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
            $query = User::query();
            // Uncomment the following line if you want to apply relations
            // $this->applyRelations($query, $request);

            $users = $query->get();

            Gate::authorize('viewAll', $request->user());

            return UserResource::collection($users)->additional([
                'status' => 'success',
                'message' => 'List of users retrieved successfully.',
                'code' => ApiResponse::OK_STATUS,
            ]);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving users.',
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
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = new User($validatedData['data']);
            $user->save();

            return ApiResponse::success(new UserResource($user), 'User created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'CREATE_FAILED',
                'Error while creating user.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }

    public function getUserById(Request $request, $id)
    {
        try {
            Gate::authorize('view', $request->user());
            $user = User::where('id', $id);
            // Uncomment the following line if you want to apply relations
            // $this->applyRelations($user, $request);

            $user = $user->first();
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $userResource = new UserResource($user);

            return ApiResponse::success($userResource, 'User retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FETCH_FAILED',
                'Error while retrieving user.',
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
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('update', $user);

            $user->update($validatedData['data']);

            return ApiResponse::success(new UserResource($user), 'User updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating user.',
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
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('update', $user);

            $user->update($validatedData['data']);

            return ApiResponse::success(new UserResource($user), 'User updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating user.',
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
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            Gate::authorize('delete', $user);

            $user->delete();

            return ApiResponse::success([], 'User deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'DELETE_FAILED',
                'Error while deleting user.',
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
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            $user->profile_picture_url = $validatedData['data']['avatar'];
            $user->save();

            return ApiResponse::success(new UserResource($user), 'Avatar updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while updating user.',
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
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $requestUser = $request->user();

            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('disable', $user, $requestUser);
            $user->status = User::STATUS_INACTIVE;
            $user->save();

            return ApiResponse::success(new UserResource($user), 'User disabled successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while banning user.',
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
                'VALIDATION_ERROR',
                'Invalid parameters provided.',
                $validatedData['errors'],
                ApiResponse::INVALID_PARAMETERS_STATUS
            );
        }

        try {
            $requestUser = $request->user();

            $user = User::find($id);
            if (! $user) {
                return ApiResponse::error(
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }
            Gate::authorize('disable', $user, $requestUser);
            $user->status = User::STATUS_ACTIVE;
            $user->save();

            return ApiResponse::success(new UserResource($user), 'User enabled successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            return ApiResponse::error(
                'UPDATE_FAILED',
                'Error while banning user.',
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
                    'NOT_FOUND',
                    'User not found.',
                    [],
                    ApiResponse::NOT_FOUND_STATUS
                );
            }

            if ($user->isAdmin() || $user->isCook()) {
                return ApiResponse::success(['admin' => true], 'User is admin.', ApiResponse::OK_STATUS);
            } else {
                return ApiResponse::success(['admin' => false], 'User is not admin.', ApiResponse::OK_STATUS);
            }

        } catch (\Throwable $e) {
            return ApiResponse::error(
                'FUNCTION_FAILED',
                'Error while cheking if user is admin.',
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

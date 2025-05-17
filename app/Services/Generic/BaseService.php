<?php

namespace App\Services\Generic;

use App\Helpers\ApiResponse;
use App\Helpers\ValidationHelper;
use App\Services\Contracts\ServiceInterface;
use App\Traits\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class BaseService implements ServiceInterface
{
    use Authorizable;

    protected Model $model;

    public function getAll(Request $request)
    {
        try {
            $query = $this->model->query();

            $relations = $this->getRelations();
            if (! empty($relations)) {
                $query->with($relations);
            }

            $items = $query->get();

            return ($this->resourceClass())::collection($items)
                ->additional(['status' => 'success', 'message' => 'List retrieved successfully.', 'code' => ApiResponse::OK_STATUS]);
        } catch (\Throwable $e) {
            Log::error('Error fetching data', ['exception' => $e->getMessage()]);

            return ApiResponse::error('FETCH_FAILED', 'Error while retrieving data.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    /**
     * Get a menu by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById(Request $request, $id)
    {
        try {
            $query = $this->model->where('id', $id);

            $relations = $this->getRelations();
            if (! empty($relations)) {
                $query->with($relations);
            }

            $item = $query->first();

            if (! $item) {
                return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving item', ['exception' => $e->getMessage()]);

            return ApiResponse::error('NOT_FOUND', 'Item not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    /**
     * Get a menu by date.
     *
     * @param  string  $day
     * @return \Illuminate\Http\Response
     */
    public function getByDate(Request $request, $day)
    {
        try {
            $query = $this->model->where('day', $day);

            $relations = $this->getRelations();
            if (! empty($relations)) {
                $query->with($relations);
            }

            $item = $query->first();

            if (! $item) {
                return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item retrieved successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving item', ['exception' => $e->getMessage()]);

            return ApiResponse::error('NOT_FOUND', 'Item not found.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    /**
     * Create a new item.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, string $imageFieldName = 'image_url')
    {
        if (! $this->isAuthorized('create')) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        $validatedData = $this->validateRequest($request, 'store');

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $data = $validatedData['data'];
            // $data = $this->handleImageUpload($request, $data, $imageFieldName);

            $item = $this->model->create($data);
            $this->syncRelations($item, $data);
            $item->load($this->getRelations());

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item created successfully.', ApiResponse::CREATED_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error creating item', ['exception' => $e->getMessage()]);

            return ApiResponse::error('CREATE_FAILED', 'Error while creating item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function update(Request $request, $id)
    {
        $item = $this->model->find($id);
        if (! $item) {
            return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
        }

        if (! $this->isAuthorized('update', $item)) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        $validatedData = $this->validateRequest($request, 'update', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $item->update($validatedData['data']);
            $this->syncRelations($item, $validatedData['data']);
            $item->load($this->getRelations());

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error updating item', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while updating item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function patch(Request $request, $id)
    {
        $item = $this->model->find($id);
        if (! $item) {
            return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
        }

        if (! $this->isAuthorized('update', $item)) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        $validatedData = $this->validateRequest($request, 'patch', ['id' => $id]);

        if (! $validatedData['success']) {
            return ApiResponse::error('VALIDATION_ERROR', 'Invalid parameters provided.', $validatedData['errors'], ApiResponse::INVALID_PARAMETERS_STATUS);
        }

        try {
            $item->update($validatedData['data']);
            $this->syncRelations($item, $validatedData['data']);
            $item->load($this->getRelations());

            return ApiResponse::success(new ($this->resourceClass())($item), 'Item updated successfully.', ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error patching item', ['exception' => $e->getMessage()]);

            return ApiResponse::error('UPDATE_FAILED', 'Error while patching item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    public function delete(Request $request, $id)
    {
        $item = $this->model->find($id);
        if (! $item) {
            return ApiResponse::error('NOT_FOUND', 'Item not found.', [], ApiResponse::NOT_FOUND_STATUS);
        }

        if (! $this->isAuthorized('delete', $item)) {
            return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
        }

        try {
            $item->delete();

            return ApiResponse::success([], 'Item deleted successfully.', ApiResponse::NO_CONTENT_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error deleting item', ['exception' => $e->getMessage()]);

            return ApiResponse::error('DELETE_FAILED', 'Error while deleting item.', ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }

    protected function isAuthorized(string $ability, $model = null)
    {
        return $this->checkAuthorization($ability, $model ?? $this->model) === true;
    }

    abstract protected function getRelations(): array;

    abstract protected function resourceClass();

    protected function validateRequest(Request $request, $method, array $extraData = [])
    {
        return ValidationHelper::validateRequest($request, $this->model->getTable(), $method, $extraData);
    }

    protected function syncRelations($model, array $data)
    {
        foreach ($this->getSyncableRelations() as $relation) {
            if (isset($data[$relation])) {
                $model->{$relation}()->sync($data[$relation]);
            }
        }
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}

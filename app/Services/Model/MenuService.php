<?php

namespace App\Services\Model;

use App\Constants\ErrorCodes;
use App\Helpers\ApiResponse;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Services\Generic\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuService extends BaseService
{
    public function __construct()
    {
        $this->model = new Menu;
    }

    protected function getRelations(): array
    {
        return ['dishes'];
    }

    protected function resourceClass()
    {
        return MenuResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
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

            // Check if the user is authorized to show a menu by date
            // if (! $this->isAuthorized('getByDate')) {
            //     return ApiResponse::error('UNAUTHORIZED', 'No tens permisos.', [], ApiResponse::FORBIDDEN_STATUS);
            // }

            $query = $this->model->where('day', $day);

            $relations = $this->getRelations();
            if (! empty($relations)) {
                $query->with($relations);
            }

            $item = $query->first();

            if (! $item) {
                return ApiResponse::error(ErrorCodes::NOT_FOUND, config('messages.generic.not_found'), [], ApiResponse::NOT_FOUND_STATUS);
            }

            return ApiResponse::success(new ($this->resourceClass())($item), config('messages.generic.operation_success'), ApiResponse::OK_STATUS);
        } catch (\Throwable $e) {
            Log::error('Error retrieving item', ['exception' => $e->getMessage()]);

            return ApiResponse::error(ErrorCodes::NOT_FOUND, config('messages.generic.not_found'), ['exception' => $e->getMessage()], ApiResponse::INTERNAL_SERVER_ERROR_STATUS);
        }
    }
}

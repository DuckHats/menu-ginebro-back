<?php

namespace App\Services\Generic;

use App\Contracts\Importable;
use App\Helpers\ApiResponse;
use App\Jobs\ImportModelJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class ImportService
{
    protected Importable $model;

    public function __construct(Importable $model)
    {
        $this->model = $model;
    }

    public function import(Request $request)
    {
        $format = $request->input('format', 'json');
        $data = $request->input('data', []);

        if (!in_array($format, ['json', 'csv', 'xlsx'])) {
            return ApiResponse::error('INVALID_FORMAT', 'Format not supported.');
        }

        if (!is_array($data)) {
            return ApiResponse::error('INVALID_DATA', 'Data need to be an array.');
        }

        $rules = $this->model->getImportValidationRules();
        $errors = [];

        foreach ($data as $index => $item) {
            $validator = Validator::make($item, $rules);
            if ($validator->fails()) {
                $errors[$index] = $validator->errors()->toArray();
            }
        }

        if (!empty($errors)) {
            return ApiResponse::error('VALIDATION_FAILED', 'Error during validation.', $errors);
        }

        // Dispatch a job per línia
        foreach ($data as $item) {
            Bus::dispatch(new ImportModelJob(get_class($this->model), $item));
        }

        return ApiResponse::success('Import in progress.');
    }
}

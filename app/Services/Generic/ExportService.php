<?php

namespace App\Services\Generic;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'json');
        $fileName = $this->model->getTable().'_export_'.now()->timestamp;

        try {
            $data = $this->model->all();

            switch (strtolower($format)) {
                case 'json':
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Exported successfully.',
                        'data' => $data,
                    ]);

                case 'csv':
                case 'xlsx':
                    $export = new class($data) implements FromCollection, WithHeadings
                    {
                        protected Collection $data;

                        public function __construct(Collection $data)
                        {
                            $this->data = $data;
                        }

                        public function collection()
                        {
                            return $this->data->map(function ($item) {
                                return collect($item)->values();
                            });
                        }

                        public function headings(): array
                        {
                            return $this->data->first()
                                ? array_keys($this->data->first()->toArray())
                                : [];
                        }
                    };

                    $extension = $format === 'csv' ? 'csv' : 'xlsx';

                    return Excel::download($export, "$fileName.$extension");

                default:
                    return ApiResponse::error(
                        'INVALID_FORMAT',
                        'Unsupported export format. Choose json, csv, or xlsx.',
                        [],
                        ApiResponse::INVALID_PARAMETERS_STATUS
                    );
            }
        } catch (\Throwable $e) {
            Log::error('Export failed', ['exception' => $e->getMessage()]);

            return ApiResponse::error(
                'EXPORT_FAILED',
                'Error while exporting data.',
                ['exception' => $e->getMessage()],
                ApiResponse::INTERNAL_SERVER_ERROR_STATUS
            );
        }
    }
}

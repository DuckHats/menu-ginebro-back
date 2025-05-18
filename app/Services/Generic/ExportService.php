<?php

namespace App\Services\Generic;

use App\Contracts\Exportable;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    protected Exportable $model;

    public function __construct(Exportable $model)
    {
        $this->model = $model;
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'json');
        $fileName = class_basename($this->model) . '_export_' . now()->timestamp;

        try {
            $data = $this->model->getExportData();
            $headings = $this->model->getExportHeadings();

            switch (strtolower($format)) {
                case 'json':
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Exported successfully.',
                        'data' => $data,
                    ]);

                case 'csv':
                case 'xlsx':
                    $export = new class($data, $headings) implements FromArray, WithHeadings
                    {
                        protected Collection $data;
                        protected array $headings;

                        public function __construct(Collection $data, array $headings)
                        {
                            $this->data = $data;
                            $this->headings = $headings;
                        }

                        public function array(): array
                        {
                            return $this->data->map(function ($row) {
                                return is_array($row) ? $row : $row->toArray();
                            })->toArray();
                        }

                        public function headings(): array
                        {
                            return $this->headings;
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

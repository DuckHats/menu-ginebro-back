<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface Exportable
{
    public function getExportData(): Collection;

    public function getExportHeadings(): array;
}

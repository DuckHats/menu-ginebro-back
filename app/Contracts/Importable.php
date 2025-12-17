<?php

namespace App\Contracts;

interface Importable
{
    public function getImportValidationRules(): array;

    public function importRow(array $data): void;

    public function preprocessImportData(array $data): array;
}

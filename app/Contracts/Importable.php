<?php

namespace App\Contracts;

interface Importable
{

    public function getImportValidationRules(): array;

    public function importRow(array $data): void;
}

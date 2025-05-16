<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface ServiceInterface
{
    public function getAll(Request $request);

    public function getById(Request $request, $id);

    public function create(Request $request);

    public function update(Request $request, $id);

    public function delete(Request $request, $id);
}

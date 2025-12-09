<?php

namespace App\Http\Controllers;

use App\Services\Model\AllergyService;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    private $allergyService;
    public function __construct(AllergyService $allergyService) {
        $this->allergyService = $allergyService;
    }
    public function index(Request $request)
    {
        return $this->allergyService->getAll($request);
    }

    public function updateUserAllergies(Request $request)
    {
        return $this->allergyService->updateUserAllergies($request);
    }
}

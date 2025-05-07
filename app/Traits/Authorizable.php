<?php

namespace App\Traits;

use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

trait Authorizable
{
    /**
     * Comprova si l'usuari té permís per a una acció sobre un model.
     *
     * @param  mixed  $model
     * @return \Illuminate\Http\JsonResponse|bool
     */
    public function checkAuthorization(string $ability, $model)
    {
        $user = Auth::user();

        if (! $user || ! Gate::allows($ability, $model)) {
            return ApiResponse::error(
                'UNAUTHORIZED',
                'No tens permisos per fer aquesta acció.',
                [],
                ApiResponse::FORBIDDEN_STATUS
            );
        }

        return true;
    }
}

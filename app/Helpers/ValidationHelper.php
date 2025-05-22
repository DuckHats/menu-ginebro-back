<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class ValidationHelper
{
    // * @param string $entity
    // * @param string $action
    // * @param array $placeholders
    // * @return array
    // */ Obtener las reglas de validación de un archivo config, reemplazando los placeholders y devolviendo un array de reglas de validación para un recurso específico.
    public static function getValidationRules(string $entity, string $action, array $placeholders = []): array
    {
        $rules = config("validation.{$entity}.{$action}", []);

        // Reemplazar los placeholders en las reglas de validación // Ex: {id}
        foreach ($placeholders as $key => $value) {
            $rules = array_map(function ($rule) use ($key, $value) {
                return str_replace("{{$key}}", $value, $rule);
            }, $rules);
        }

        return $rules;
    }

    // * @param \Illuminate\Http\Request $request
    // * @param string $entity
    // * @param string $action
    // * @param array $placeholders
    // * @return array
    // */ Validar una solicitud con las reglas de validación sacadas de la funcion getValidationRules y devolver un array con el resultado de la validación.
    public static function validateRequest($request, string $entity, string $action, array $placeholders = [])
    {
        $rules = self::getValidationRules($entity, $action, $placeholders);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return [
                'success' => false,
                'errors' => $validator->errors(),
            ];
        }

        return [
            'success' => true,
            'data' => $validator->validated(),
        ];
    }
}

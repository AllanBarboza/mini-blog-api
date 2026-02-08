<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:admins,username'],
            'password' => ['required', 'string', 'min:8']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $statusCode = 422;

        if ($errors->has('username')) {
            $failedRules = $validator->failed();

            if (isset($failedRules['username']['Unique'])) {
                $statusCode = 409;
            }
        }

        throw new HttpResponseException(response()->json([
            'message' => 'Falha na validação dos dados.',
            'errors' => $errors
        ], $statusCode));
    }
}

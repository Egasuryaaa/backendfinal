<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'timestamp' => now()->toISOString(),
            ], 422)
        );
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute field is required',
            'email' => ':attribute must be a valid email address',
            'min' => ':attribute must be at least :min characters',
            'max' => ':attribute may not be greater than :max characters',
            'unique' => ':attribute has already been taken',
            'confirmed' => ':attribute confirmation does not match',
            'exists' => 'Selected :attribute is invalid',
            'image' => ':attribute must be an image',
            'mimes' => ':attribute must be a file of type: :values',
            'numeric' => ':attribute must be a number',
            'integer' => ':attribute must be an integer',
            'in' => 'Selected :attribute is invalid',
        ];
    }
}

<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string'],
            'new_password'      => ['required', 'string'],
            'confirm_new_password' => ['required', 'same:new_password'],
        ];
    }
    //coustom error message
    public function messages()
    {
        return [
            'old_password.required' => 'Old Password is required',
            'new_password.required' => 'Password is required',
        ];
    }
    //change validation error response
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'message' => $validator->errors()->first(),
            'data' => null,
        ], 422));
    }
}

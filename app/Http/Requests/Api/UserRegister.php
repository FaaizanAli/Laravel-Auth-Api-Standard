<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegister extends FormRequest
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
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'fcm_token'     => ['string'],
//            'type'          => ['string', Rule::in([
//                'admin',
//                'client'
//            ])],
            'password'      => ['required', 'string'],
            'confirm_password' => ['required', 'same:password'],
            'image'        => ['file', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],

        ];
    }
    //coustom error message
    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'email.unique' => 'Email already exist',
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

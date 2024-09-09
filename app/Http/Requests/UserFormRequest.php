<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserFormRequest extends FormRequest
{
    /**
     * Indicates if the validation should stop on the first failure
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'password' => 'required|string|min:8|confirmed',
        ];
        // Adjust rules for PATCH or PUT requests
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules = [
                'name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'nullable|string|email|max:255',
                'password' => 'nullable|string|min:8|confirmed',

            ];
        }

        return $rules;
    }
    /**
     * Get the custom validation messages
     *
     * @return array<string, string>
     */

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'string' => ':attribute must be a string',
            'email' => ':attribute must be a valid email address',
            'unique' => ':attribute has already been taken',
            'min' => ':attribute must be at least 8 characters',
            'max' => ':attribute may not be greater than 255 characters',
            'confirmed' => ':attribute confirmation does not match',
            'regex' => ':attribute must contain only letters',
            'exists' => 'The :attribute must exist in the roles table.',

        ];
    }
    /**
     * Get custom attributes for validator errors
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
        ];
    }
    /**
     * Handle a failed validation attempt
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'validation error',
            'errors' => $validator->errors(),
        ], 400));
    }
}

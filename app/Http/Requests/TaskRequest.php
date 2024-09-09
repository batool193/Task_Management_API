<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorty;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class TaskRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure
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
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'priority' => ['required',  new Enum(TaskPriorty::class)],
            'assigned_to' => 'required|integer|exists:users,id',

        ];

        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules = [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'priority' => ['nullable', new Enum(TaskPriorty::class)],
            ];
        }
        return $rules;
    }

    /**
     * Get the custom messages for validator errors
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'The :attribute is required.',
            'Enum' => 'The status must be a valid enum value.',
            'exists' => 'The :attribute must exist in the users table.',
        ];
    }
    /**
     * Get custom attributes for validator errors
     *
     * @return array
     */

    public function attributes()
    {
        return [
            'title' => 'task title',
            'description' => 'task description',
            'priority' => 'task priority',
            'assigned_to' => 'assigned user',

        ];
    }
    /**
     * Handle a failed validation attempt
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Validation\ValidationException
     */

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'message' => 'validation error',
            'errors' => $validator->errors()
        ], 400));
    }
}

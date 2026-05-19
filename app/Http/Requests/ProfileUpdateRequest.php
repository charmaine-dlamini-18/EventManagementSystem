<?php

namespace App\Http\Requests;

use App\Models\UserCMS;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(UserCMS::class)->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your name.',
            'name.max'      => 'Name must not exceed :max characters.',
            'email.required' => 'An email address is required.',
            'email.email'    => 'Please enter a valid email address.',
            'email.unique'   => 'This email is already in use.',
            'email.max'      => 'Email must not exceed :max characters.',
        ];
    }
}

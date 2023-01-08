<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // módosítva
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // módosítva
        return [
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'address' => ['required', 'string', 'min:1', 'max:255'],
            'telephone' => ['required', 'string', 'min:10', 'max:255'],
            'email' => ['required', 'string', 'min:1', 'max:255', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:10', 'max:255', 'confirmed'],

            'accept1' => ['required', 'boolean'],
            'accept2' => ['required', 'boolean'],
        ];
    }
}

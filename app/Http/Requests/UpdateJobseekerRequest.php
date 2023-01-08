<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobseekerRequest extends FormRequest
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
            // 'id' => ['required', 'min:1'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'address' => ['required', 'string', 'min:1', 'max:255'],
            'telephone' => ['required', 'string', 'min:10', 'max:255'],
        ];
    }
}

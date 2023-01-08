<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobapplicationRequest extends FormRequest
{
    /**
     * Determine if the jobseeker is authorized to make this request.
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
            'user_id' => ['required', 'min:1'],
            'jobadvertisement_id' => ['required', 'min:1'],
            'cv_id' => ['required', 'min:1'],
            'status' => ['required', 'min:1'],
        ];
    }
}
